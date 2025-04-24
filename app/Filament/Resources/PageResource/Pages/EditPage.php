<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Database\Eloquent\Model;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $uuid = (string) Str::uuid();

        $reorderData = array_values($data['pages']);
        $languageId = $reorderData[0]['language_id'] ?? null;
        $code = $reorderData[0]['code'] ?? null;

        if ($languageId && $code) {
            $existingPage = Page::where('language_id', $languageId)
                ->where('code', $code)
                ->first();

            if ($existingPage && $existingPage->group_id) {
                $uuid = $existingPage->group_id;
            }

            $data['pages'][$code]['group_id'] = $uuid;
            $data['pages'][$code]['created_by'] = auth()->id();
            $data['pages'][$code]['updated_by'] = auth()->id();
        }

        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $languageCode = Language::find($record->language_id)->code;

        if (isset($data['pages'][$languageCode])) {
            $record->update($data['pages'][$languageCode]);
        }

        return $record;
    }

}
