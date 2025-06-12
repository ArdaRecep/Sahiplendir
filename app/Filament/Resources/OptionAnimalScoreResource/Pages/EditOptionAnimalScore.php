<?php

namespace App\Filament\Resources\OptionAnimalScoreResource\Pages;

use App\Filament\Resources\OptionAnimalScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOptionAnimalScore extends EditRecord
{
    protected static string $resource = OptionAnimalScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
