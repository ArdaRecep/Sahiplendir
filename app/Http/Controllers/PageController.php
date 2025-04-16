<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $models = [
        'page' => [
            'model' => Page::class,
            'slug_field' => 'slug',
            'language_field' => 'language_id'
        ],
    ];

    /**
     * Sayfa gösterme metodu
     */
    public function show($lang = null, $slug = null)
    {
        // Dil ve slug parametreleri yoksa, ziyaretçinin diline göre ana sayfa gösterilecek
        if (!$lang && !$slug) {
            return $this->renderPage($this->getHomePageByVisitorLanguage());
        }

        // Dil parametresi varsa, geçerli dil kontrol edilir
        $language = $this->validateLanguage($lang);
        if ($slug && $language) {
            // Dil ve slug varsa, sayfa sorgulanır
            $page = $this->getModelPage($slug, $language->id);
            if (!$page) {
                abort(404, "The requested page does not exist.");
            }
            return $this->renderPage($page);
        }

        // Dil var fakat slug yoksa, ilgili dilde ana sayfa gösterilir
        if ($language && !$slug) {
            return $this->renderPage($this->getHomePageByLanguageId($language->id));
        }

        // Dil yanlış veya eksikse, 404 döndürülür
        return abort(404, "The requested page does not exist.");
    }

    /**
     * Ziyaretçinin tarayıcı diline göre ana sayfa getirir
     */
    protected function getHomePageByVisitorLanguage()
    {
        $langCode = $this->getVisitorBrowserLanguage();
        $language = Language::where('name', $langCode)->first();

        if (!$language) {
            return $this->getHomePageByNativeLanguage();
        }

        return $this->getHomePageByLanguageId($language->id);
    }

    /**
     * Verilen dili geçerli olup olmadığını kontrol eder
     */
    protected function validateLanguage($lang)
    {
        return Language::where('name', $lang)->first();
    }

    /**
     * Slug ve dil id'ye göre sayfayı getirir
     */
    protected function getModelPage($slug, $languageId)
    {
        $modelInfo = $this->models['page'];
        $page = $modelInfo['model']::where($modelInfo['slug_field'], $slug)
            ->where($modelInfo['language_field'], $languageId)
            ->first();

        return $page;
    }

    /**
     * Sayfayı render eder
     */
    protected function renderPage($page)
    {
        $viewName = $page->getTable();
        $viewPath = $viewName === 'pages' ? $viewName : "details.$viewName";

        // Sayfanın view dosyası var mı kontrol edilir
        if (!view()->exists($viewPath)) {
            abort(404, "The view for {$viewPath} does not exist.");
        }

        return view($viewPath, compact('page'));
    }

    /**
     * Dil ID'sine göre ana sayfayı getirir
     */
    protected function getHomePageByLanguageId($languageId)
    {
        $page = Page::where('language_id', $languageId)->where('is_home', true)->first();
        if (!$page) {
            abort(404, "Home page not found for language ID: {$languageId}");
        }
        return $page;
    }

    /**
     * Ana dildeki sayfayı getirir
     */
    protected function getHomePageByNativeLanguage()
    {
        $nativeLangId = Language::where('is_native', true)->firstOrFail()->id;
        return $this->getHomePageByLanguageId($nativeLangId);
    }

    /**
     * Ziyaretçinin tarayıcı dilini döndürür
     */
    protected function getVisitorBrowserLanguage()
    {
        $acceptLanguage = request()->server('HTTP_ACCEPT_LANGUAGE');
        return $acceptLanguage ? substr($acceptLanguage, 0, 2) : null;
    }
}