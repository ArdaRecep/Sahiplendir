<?php

namespace App\Filament\Resources\SubCategoryResource\Pages;

use App\Filament\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;  // Notification sınıfını dahil ediyoruz.
use App\Models\Language;

class CreateSubCategory extends CreateRecord
{
    protected static string $resource = SubCategoryResource::class;

        protected function mutateFormDataBeforeCreate(array $data): array
    {
        $uuid = (string) Str::uuid(); // Her yeni sayfa için benzersiz bir çeviri grup ID'si oluşturuluyor

        $all_languages = Language::select('id', 'name', 'code')->get();

        // Sayfa verilerini her dil için düzenliyoruz
        foreach ($all_languages as $language) {
            // 'sub_categories' içinde dilin verilerinin olup olmadığını kontrol et
            if (empty($data['sub_categories'][$language->code]['name'])) {
                // Türkçe veya diğer diller için dil ismini dinamik olarak alıyoruz
                Notification::make()
                    ->danger()
                    ->title($language->name . ' dili için gerekli alanlar eksik.')
                    ->send();

                $this->halt(); // İşlemi durduruyoruz
            }

            // Veriye gerekli eklemeleri yapıyoruz
            $data['sub_categories'][$language->code]['created_by'] = auth()->id();
            $data['sub_categories'][$language->code]['updated_by'] = auth()->id();
            $data['sub_categories'][$language->code]['slug'] = Str::slug($data['sub_categories'][$language->code]['name']);
            $data['sub_categories'][$language->code]['group_id'] = $uuid;
            $data['sub_categories'][$language->code]['language_id'] = $language->id;
        }
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $all_languages = Language::select('id', 'code')->get();

        foreach ($all_languages as $language) {
            SubCategory::create($data['sub_categories'][$language->code]);
        }

        // Filament'in beklediği şekilde bir Model döndürmek için oluşturulan ilk kaydı döndürüyoruz.
        return SubCategory::where('group_id', $data['sub_categories'][$all_languages->first()->code]['group_id'])->first();
    }
    protected function getRedirectUrl(): string
    {
        $nativeLanguage = Language::where('code', 'tr')->first(); // is_native varsa onunla değiştir

        if ($nativeLanguage) {
            $subCategory = SubCategory::where('group_id', $this->record->group_id)
                ->where('language_id', $nativeLanguage->id)
                ->first();

            if ($subCategory) {
                return route('filament.admin.resources.sub-categories.edit', ['record' => $subCategory->id]);
            }
        }

        return parent::getRedirectUrl(); // fallback
    }
}
