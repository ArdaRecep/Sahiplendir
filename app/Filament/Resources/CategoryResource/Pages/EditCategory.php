<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Language;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;


class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    // Load existing translations into the form
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $groupId = $this->record->group_id;
        $allLanguages = Language::select('id', 'code')->get();

        $groupData = [];
        foreach ($allLanguages as $language) {
            $translation = Category::where('group_id', $groupId)
                ->where('language_id', $language->id)
                ->first();

            $groupData[$language->code] = [
                'name'        => $translation->name,
                'slug'        => $translation->slug,
                'created_by'  => $translation->created_by,
                'updated_by'  => $translation->updated_by,
                'language_id' => $language->id,
                'group_id'    => $groupId,
            ];
        }

        $data['categories'] = $groupData;

        return $data;
    }

    // Prepare submitted data before saving
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $groupId = $this->record->group_id;
        $allLanguages = Language::select('id', 'code', 'name')->get();

        foreach ($allLanguages as $language) {
            if (empty($data['categories'][$language->code]['name'])) {
                Notification::make()
                    ->danger()
                    ->title($language->name . ' dili için gerekli alanlar eksik.')
                    ->send();

                $this->halt();
            }

            $entry =& $data['categories'][$language->code];
            $entry['slug']        = Str::slug($entry['name']);
            $entry['updated_by']  = auth()->id();
            $entry['group_id']    = $groupId;
            $entry['language_id'] = $language->id;
        }

        return $data;
    }

    // Update each translation record
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $allLanguages = Language::select('id', 'code')->get();

        foreach ($allLanguages as $language) {
            $payload = $data['categories'][$language->code];
            $category = Category::where('group_id', $payload['group_id'])
                ->where('language_id', $language->id)
                ->first();

            if ($category) {
                $category->update([
                    'name'       => $payload['name'],
                    'slug'       => $payload['slug'],
                    'updated_by' => $payload['updated_by'],
                ]);
            }
        }

        // Return the native-language record for the redirect
        $native = Language::where('code', 'tr')->first();
        return Category::where('group_id', $this->record->group_id)
            ->where('language_id', $native?->id)
            ->first();
    }

    // Customize header actions
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // After save, redirect to native-language edit view
    protected function getRedirectUrl(): string
    {
        $native = Language::where('code', 'tr')->first();

        if ($native) {
            $category = Category::where('group_id', $this->record->group_id)
                ->where('language_id', $native->id)
                ->first();

            if ($category) {
                return route('filament.admin.resources.categories.edit', ['record' => $category->id]);
            }
        }

        return parent::getRedirectUrl();
    }
}
