<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            // Dil belirleme: örnek olarak session veya route parametresinden    
            $siteSetting = null;
    
            if (isset($view->page->language_id) && Schema::hasTable('site_settings')) {
                $siteSetting = SiteSetting::where('language_id', $view->page->language_id)->first();
            }
    
            $view->with([
                'title' => $siteSetting->data['name'] ?? config('app.name'),
                'logo' => isset($siteSetting->logo) ? Storage::url($siteSetting->logo) : '',
                'footer_logo' => isset($siteSetting->footer_logo) ? Storage::url($siteSetting->footer_logo) : '',
                'fav_icon' => isset($siteSetting->fav_icon) ? Storage::url($siteSetting->fav_icon) : '',
            ]);
        });
    }
}
