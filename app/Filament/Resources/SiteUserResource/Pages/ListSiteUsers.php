<?php

namespace App\Filament\Resources\SiteUserResource\Pages;

use App\Filament\Resources\SiteUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteUsers extends ListRecords
{
    protected static string $resource = SiteUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
