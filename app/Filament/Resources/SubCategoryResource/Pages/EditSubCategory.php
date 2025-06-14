<?php

namespace App\Filament\Resources\SubCategoryResource\Pages;

use App\Filament\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;

class EditSubCategory extends EditRecord
{
    protected static string $resource = SubCategoryResource::class;

    // 1) Formu doldurmadan önce, tüm çevirileri data array’ine ekliyoruz
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $allLanguages = Language::select('id', 'code')->get();
        $translations = [];

        foreach ($allLanguages as $lang) {
            $item = SubCategory::where('group_id', $this->record->group_id)
                        ->where('language_id', $lang->id)
                        ->first();
            $translations[$lang->code] = [
                'name'        => $item->name        ?? null,
                'description' => $item->description ?? null,
            ];
        }

        $data['sub_categories'] = $translations;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $allLanguages = Language::select('id', 'code', 'name')->get();

        foreach ($allLanguages as $lang) {
            $entry = $data['sub_categories'][$lang->code] ?? [];
            
            if (empty($entry['name'])) {
                Notification::make()
                    ->danger()
                    ->title("{$lang->name} dili için gerekli alanlar eksik.")
                    ->send();
                $this->halt();
            }
        }

        return $data;
    }

    // 3) Güncelleme işlemini tüm dillere uyguluyoruz
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $allLanguages = Language::select('id', 'code')->get();

        foreach ($allLanguages as $lang) {
            SubCategory::where('group_id', $this->record->group_id)
                ->where('language_id', $lang->id)
                ->update($data['sub_categories'][$lang->code]);
        }

        // Yine Türkçe kaydı döndürüyoruz ki redirect doğru olsun
        $native = Language::where('code', 'tr')->first();
        if ($native) {
            return SubCategory::where('group_id', $this->record->group_id)
                ->where('language_id', $native->id)
                ->first();
        }

        return $this->record;
    }

    // 4) Güncelledikten sonra da Türkçe kaydın edit ekranına dönsün
    protected function getRedirectUrl(): string
    {
        $native = Language::where('code', 'tr')->first();
        if ($native) {
            $sub = SubCategory::where('group_id', $this->record->group_id)
                ->where('language_id', $native->id)
                ->first();

            if ($sub) {
                return route('filament.admin.resources.sub-categories.edit', [
                    'record' => $sub->id,
                ]);
            }
        }

        return parent::getRedirectUrl();
    }

    // Header'da sadece silme butonu kalsın
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
