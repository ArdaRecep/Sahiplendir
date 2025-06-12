<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\Post;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $uuid = (string) Str::uuid(); // Create a unique group ID for new pages

        $all_languages = Language::select('id', 'name', 'code')
            ->get();

        foreach ($all_languages as $language) {
            $data['post'][$language->code]['group_id'] = $uuid;
            $data['post'][$language->code]['slug'] = Str::slug($data['post'][$language->code]['title']);
            $data['post'][$language->code]['language_id'] = $language->id;
            $data['post'][$language->code]['image'] = $data['image'];

        }
        unset($data['image']);


        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $all_languages = Language::select('id', 'name', 'code')->get();

        foreach ($all_languages as $language) {
            $post = Post::create($data['post'][$language->code]);
            // Kategorileri ilişkilendir
            if (isset($data['post'][$language->code]['category'])) {
                $post->postCategories()->sync($data['post'][$language->code]['category']);
            }
        }

        // Filament'in beklediği şekilde bir Model döndürmek için oluşturulan ilk kaydı döndürüyoruz.
        return Post::where('group_id', $data['post'][$all_languages->first()->code]['group_id'])->first();
    }
}
