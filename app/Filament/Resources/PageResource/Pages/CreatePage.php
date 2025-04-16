<?php
namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use App\Models\Language;
use App\Models\Page;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;  // Notification sınıfını dahil ediyoruz.

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $uuid = (string) Str::uuid(); // Her yeni sayfa için benzersiz bir çeviri grup ID'si oluşturuluyor

        $all_languages = Language::select('id', 'name', 'code')->get();

        // Sayfa verilerini her dil için düzenliyoruz
        foreach ($all_languages as $language) {
            // 'pages' içinde dilin verilerinin olup olmadığını kontrol et
            if (empty($data['pages'][$language->code]['name'])) {
                // Türkçe veya diğer diller için dil ismini dinamik olarak alıyoruz
                Notification::make()
                    ->danger()
                    ->title($language->name . ' dili için gerekli alanlar eksik.')
                    ->send();

                $this->halt(); // İşlemi durduruyoruz
            }

            // Veriye gerekli eklemeleri yapıyoruz
            $data['pages'][$language->code]['created_by'] = auth()->id();
            $data['pages'][$language->code]['updated_by'] = auth()->id();
            $data['pages'][$language->code]['slug'] = Str::slug($data['pages'][$language->code]['name']);
            $data['pages'][$language->code]['group_id'] = $uuid;
            $data['pages'][$language->code]['language_id'] = $language->id;
        }
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $all_languages = Language::select('id', 'code')->get();

        foreach ($all_languages as $language) {
            Page::create($data['pages'][$language->code]);
        }

        // Filament'in beklediği şekilde bir Model döndürmek için oluşturulan ilk kaydı döndürüyoruz.
        return Page::where('group_id', $data['pages'][$all_languages->first()->code]['group_id'])->first();
    }
}

