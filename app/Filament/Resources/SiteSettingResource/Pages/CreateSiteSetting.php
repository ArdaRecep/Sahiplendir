<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Actions;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteSetting extends CreateRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $uuid = (string) Str::uuid(); // Create a unique translation group ID for new pages

        $all_languages = Language::select('id', 'code')
            ->get();

        foreach ($all_languages as $language) {
            $data['site_settings'][$language->code]['group_id'] = $uuid;
            $data['site_settings'][$language->code]['language_id'] = $language->id;
            $data['site_settings'][$language->code]['logo'] = $data['logo'] ?? null;
            $data['site_settings'][$language->code]['footer_logo'] = $data['footer_logo'] ?? null;
            $data['site_settings'][$language->code]['fav_icon'] = $data['fav_icon'] ?? null;
        }
        unset($data['logo'], $data['footer_logo'], $data['fav_icon']);

        return $data;
    }
    protected function handleRecordCreation(array $data): Model
    {
        $all_languages = Language::select('id', 'code')->get();

        foreach ($all_languages as $language) {
            SiteSetting::create($data['site_settings'][$language->code]);
        }

        // Filament'in beklediği şekilde bir Model döndürmek için oluşturulan ilk kaydı döndürüyoruz.
        return SiteSetting::where('group_id', $data['site_settings'][$all_languages->first()->code]['group_id'])->first();
    }
}
