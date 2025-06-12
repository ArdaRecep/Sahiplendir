<?php

namespace App\Filament\Resources\TestQuestionResource\Pages;

use App\Filament\Resources\TestQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestQuestion extends CreateRecord
{
    protected static string $resource = TestQuestionResource::class;
}
