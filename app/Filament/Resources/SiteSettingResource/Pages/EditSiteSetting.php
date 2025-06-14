<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Language;
use Illuminate\Support\Str;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Model;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $nativeLanguage = Language::where('is_native', 1)->first();


        if ($nativeLanguage) {
            $existingSiteSetting = SiteSetting::where('language_id', $data['site_settings'][$nativeLanguage->code]['language_id'])->first();

            if ($existingSiteSetting && $existingSiteSetting->group_id) {
                $uuid = $existingSiteSetting->group_id;
            } else {
                $uuid = (string) Str::uuid();
            }
        } else {
            $uuid = (string) Str::uuid();
        }

        $all_languages = Language::select('id', 'code')
            ->get();

        foreach ($all_languages as $language) {
            $data['site_settings'][$language->code]['language_id'] = $language->id;
            $data['site_settings'][$language->code]['group_id'] = $uuid;
            $data['site_settings'][$language->code]['footer_text'] = $data['footer_text'] ?? null;
            $data['site_settings'][$language->code]['logo'] = $data['logo'] ?? null;
            $data['site_settings'][$language->code]['footer_logo'] = $data['footer_logo'] ?? null;
            $data['site_settings'][$language->code]['fav_icon'] = $data['fav_icon'] ?? null;
        }

        unset($data['logo'], $data['footer_logo'], $data['fav_icon'], $data['footer_text']);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $all_site_settings = SiteSetting::where('group_id', $record->group_id)->get();
        foreach ($all_site_settings as $site_setting) {

            $languageName = Language::find($site_setting->language_id)->code;

            $site_setting->update($data['site_settings'][$languageName]);
        }

        // 1. Tüm dillerin listesini al
        $all_languages = Language::all()->pluck('id', 'code')->toArray(); // Dil kodu ile ID'yi alıyoruz

        // 2. Mevcut sayfaların dil ID'lerini al (group_id'ye göre)
        $existing_languages = SiteSetting::where('group_id', $record->group_id)
            ->pluck('language_id')
            ->toArray();

        // 3. Eksik dilleri bul: tüm dillerden mevcut olanları çıkar
        $missing_languages = array_diff($all_languages, $existing_languages);

        // 4. Eksik olan diller için sayfa oluştur
        foreach ($missing_languages as $languageCode => $languageId) {
            if (isset($data['site_settings'][$languageCode])) {
                SiteSetting::create([
                    'data' => $data['site_settings'][$languageCode]['data'] ?? null,
                    'language_id' => $data['site_settings'][$languageCode]['language_id'] ?? null,
                    'footer_text' => $data['site_settings'][$languageCode]['footer_text'] ?? null,
                    'logo' => $data['site_settings'][$languageCode]['logo'] ?? null,
                    'footer_logo' => $data['site_settings'][$languageCode]['footer_logo'] ?? null,
                    'fav_icon' => $data['site_settings'][$languageCode]['fav_icon'] ?? null,
                    'group_id' => $record->group_id,
                ]);
            }
        }

        return $record;
    }
}
