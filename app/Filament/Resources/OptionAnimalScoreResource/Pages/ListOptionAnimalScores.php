<?php

namespace App\Filament\Resources\OptionAnimalScoreResource\Pages;

use App\Filament\Resources\OptionAnimalScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOptionAnimalScores extends ListRecords
{
    protected static string $resource = OptionAnimalScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

}
