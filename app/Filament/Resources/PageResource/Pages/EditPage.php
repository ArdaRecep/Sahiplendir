<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Database\Eloquent\Model;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $nativeLanguage = Language::where('is_native', 1)->first();

        $uuid = (string) Str::uuid(); 

        if ($nativeLanguage) {
            $reorderData = array_values($data['pages']);
            // Yalnızca aynı içeriğe sahip dillerin `group_id` değerini günceller
            $existingPage = Page::where('language_id', $reorderData[0]['language_id'])
            ->where('code', $reorderData[0]['code'])->first();

            if ($existingPage && $existingPage->group_id) {
                $uuid = $existingPage->group_id;
            }
        }
        $all_languages = Language::select('id', 'name', 'code')->get();

    foreach ($all_languages as $language) {
        $data['pages'][$language->code]['created_by'] = auth()->id();
        $data['pages'][$language->code]['updated_by'] = auth()->id();
        $data['pages'][$language->code]['group_id'] = $uuid;
    }

    return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $all_pages = Page::where('group_id', $record->group_id)->get();

        foreach ($all_pages as $page) {

            $languageCode = Language::find($page->language_id)->code;

            $page->update($data['pages'][$languageCode]);
        }
        $all_languages = Language::all()->pluck('id', 'code')->toArray(); // Dil kodu ile ID'yi alıyoruz
        $existing_languages = Page::where('group_id', $record->group_id)
            ->pluck('language_id')
            ->toArray();
        $missing_languages = array_diff($all_languages, $existing_languages);
        

        foreach ($missing_languages as $languageCode => $languageId) {
            if (isset($data['pages'][$languageCode])) {
                Page::create([
                    'name' => $data['pages'][$languageCode]['name'] ?? '',
                    'slug' => $data['pages'][$languageCode]['slug'] ?? '',
                    'redirect_url' => $data['pages'][$languageCode]['redirect_url'] ?? [],
                    'description' => $data['pages'][$languageCode]['description'] ?? null,
                    'meta' => $data['pages'][$languageCode]['meta'] ?? '',
                    'header_scripts' => $data['pages'][$languageCode]['header_scripts'] ?? null,
                    'footer_scripts' => $data['pages'][$languageCode]['footer_scripts'] ?? null,
                    'content' => $data['pages'][$languageCode]['content'] ?? null,
                    'is_home' => $data['pages'][$languageCode]['is_home'] ?? 0,
                    'published_at' => $data['pages'][$languageCode]['published_at'] ?? null,
                    'language_id' => $languageId, // Eksik dilin ID'si
                    'group_id' => $record->group_id,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);
            }
        }

        return $record;
    }
}
