<?php

namespace App\Filament\Resources\SiteUserResource\Pages;

use App\Filament\Resources\SiteUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteUser extends EditRecord
{
    protected static string $resource = SiteUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
