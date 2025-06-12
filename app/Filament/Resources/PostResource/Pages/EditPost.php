<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\Models\Language;
use App\Models\Post;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Form verisini olduğu gibi bırakır; hiçbir UUID/slug/group-id üretmez ya da değiştirmez.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    /**
     * Mevcut çevirileri günceller.
     * Yeni Post kaydı oluşturmaz ve group_id'ye dokunmaz.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $groupId = $record->group_id;

        foreach ($data['post'] as $langCode => $attrs) {
            $languageId = $attrs['language_id'] ?? null;
            if (! $languageId) {
                continue;
            }

            // Aynı grup ve dil için mevcut kaydı bul
            $existing = Post::where('group_id', $groupId)
                            ->where('language_id', $languageId)
                            ->first();

            if (! $existing) {
                continue;
            }

            // Güncellemek istemediğiniz alanları çıkarın
            $updatable = Arr::except($attrs, [
                'language_id',
                'group_id',
                'category',      // ilişki dizisi
                // ek korumak istediğiniz başka anahtarlar varsa buraya
            ]);

            // Dinamik olarak tüm diğer alanları doldur ve kaydet
            $existing->fill($updatable)->save();

            // Kategori ilişkisini güncelle
            if (isset($attrs['category'])) {
                $existing->postCategories()->sync($attrs['category']);
            }
        }

        return $record;
    }
}
