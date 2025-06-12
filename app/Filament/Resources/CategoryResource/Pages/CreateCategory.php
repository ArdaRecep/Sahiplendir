<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;  // Notification sınıfını dahil ediyoruz.
use App\Models\Language;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $uuid = (string) Str::uuid(); // Her yeni sayfa için benzersiz bir çeviri grup ID'si oluşturuluyor

        $all_languages = Language::select('id', 'name', 'code')->get();

        // Sayfa verilerini her dil için düzenliyoruz
        foreach ($all_languages as $language) {
            // 'categories' içinde dilin verilerinin olup olmadığını kontrol et
            if (empty($data['categories'][$language->code]['name'])) {
                // Türkçe veya diğer diller için dil ismini dinamik olarak alıyoruz
                Notification::make()
                    ->danger()
                    ->title($language->name . ' dili için gerekli alanlar eksik.')
                    ->send();

                $this->halt(); // İşlemi durduruyoruz
            }

            // Veriye gerekli eklemeleri yapıyoruz
            $data['categories'][$language->code]['created_by'] = auth()->id();
            $data['categories'][$language->code]['updated_by'] = auth()->id();
            $data['categories'][$language->code]['slug'] = Str::slug($data['categories'][$language->code]['name']);
            $data['categories'][$language->code]['group_id'] = $uuid;
            $data['categories'][$language->code]['language_id'] = $language->id;
        }
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $all_languages = Language::select('id', 'code')->get();

        foreach ($all_languages as $language) {
            Category::create($data['categories'][$language->code]);
        }

        // Filament'in beklediği şekilde bir Model döndürmek için oluşturulan ilk kaydı döndürüyoruz.
        return Category::where('group_id', $data['categories'][$all_languages->first()->code]['group_id'])->first();
    }
    protected function getRedirectUrl(): string
    {
        $nativeLanguage = Language::where('code', 'tr')->first(); // is_native varsa onunla değiştir

        if ($nativeLanguage) {
            $category = Category::where('group_id', $this->record->group_id)
                ->where('language_id', $nativeLanguage->id)
                ->first();

            if ($category) {
                return route('filament.admin.resources.categories.edit', ['record' => $category->id]);
            }
        }

        return parent::getRedirectUrl(); // fallback
    }
}
