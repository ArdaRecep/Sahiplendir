<?php

namespace App\Helpers;

use App\Models\Page;
use App\Models\Section as SectionModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;

class FormHelpers
{
    public static function getSectionSettings()
    {
        return Section::make("Section Settings")
            ->description("You can Manage Section Settings")
            ->schema([
                TextInput::make('name')
                    ->prefixIcon('heroicon-o-view-columns', $is_inline = true)
                    ->required(),

                Select::make('section_id')
                    ->label('Section')
                    ->options(SectionModel::all()->pluck('name', 'id'))
                    ->disabledOn(['edit']) // Disabled in edit mode
                    ->required()
                    ->searchable(),

                Select::make('page_id')
                    ->label('Page')
                    ->options(Page::all()->pluck('name', 'id'))
                    ->disabledOn(['edit']) // Disabled in edit mode
                    ->required()
                    ->searchable(),
            ])
            ->collapsed();
    }
}
