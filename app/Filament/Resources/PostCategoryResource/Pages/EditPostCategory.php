<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Models\Language;
use Illuminate\Support\Arr;
use App\Models\PostCategory;

class EditPostCategory extends EditRecord
{
    protected static string $resource = PostCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Form verisini değiştirmeden olduğu gibi bırakır.
     * group_id veya slug değerlerine dokunmaz.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    /**
     * Sadece mevcut PostCategory kayıtlarını günceller.
     * Yeni kayıt oluşturmaz, group_id'yi asla değiştirmez.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Aynı gruptaki tüm çevirileri al
        $translations = PostCategory::where('group_id', $record->group_id)->get();

        foreach ($translations as $translation) {
            // Dizin anahtarını almak için dil adını bulun
            $langCode = Language::find($translation->language_id)->code;

            // Form verisinde o dil yoksa atla
            if (! isset($data['post_category'][$langCode])) {
                continue;
            }

            $attrs = $data['post_category'][$langCode];

            // Güncellemek istemediğimiz anahtarları çıkar
            $updatable = Arr::except($attrs, [
                'language_id',
                'group_id',
            ]);

            // Dinamik olarak model'i doldur ve kaydet
            $translation->fill($updatable)->save();
        }

        return $record;
    }
}
