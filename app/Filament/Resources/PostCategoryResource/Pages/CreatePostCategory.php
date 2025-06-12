<?php

namespace App\Filament\Resources\PostCategoryResource\Pages;

use App\Filament\Resources\PostCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Model;


class CreatePostCategory extends CreateRecord
{
    protected static string $resource = PostCategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $uuid = (string) Str::uuid(); // Create a unique translation group ID for new pages

        $all_languages = Language::select('id', 'name', 'code')
            ->get();

        foreach ($all_languages as $language) {
            $data['post_category'][$language->code]['group_id'] = $uuid;
            $data['post_category'][$language->code]['slug'] = Str::slug($data['post_category'][$language->code]['title']);
            $data['post_category'][$language->code]['language_id'] = $language->id;
        }

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $all_languages = Language::select('id', 'name', 'code')->get();

        foreach ($all_languages as $language) {
            PostCategory::create($data['post_category'][$language->code]);
        }

        // Filament'in beklediği şekilde bir Model döndürmek için oluşturulan ilk kaydı döndürüyoruz.
        return PostCategory::where('group_id', $data['post_category'][$all_languages->first()->code]['group_id'])->first();
    }

}
