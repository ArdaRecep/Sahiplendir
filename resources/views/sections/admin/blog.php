<?php

use App\Models\Page;
use App\Models\Section as SectionModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use App\Helpers\FormHelpers;

$update_form_inputs = [
    FormHelpers::getSectionSettings(),
    Section::make(__("admin.section_content_management"))
        ->description(__("admin.manage_section_content", ['section' => 'Blog']))
        ->schema([
            Select::make('data.type')
                ->options([
                    'home' => 'Anasayfa',
                    'all' => 'Hepsi',
                ])
                ->required()
                ->reactive(),

            TextInput::make('data.top_title')
                ->label('Üst Başlık')
                ->minLength(3)
                ->maxLength(200)
                ->visible(fn($get) => $get('data.type') === 'home'|| $get('data.type') === 'all'),

            TextInput::make('data.title')
                ->label('Başlık')
                ->minLength(3)
                ->maxLength(200)
                ->visible(fn($get) => $get('data.type') === 'home'),

                TextInput::make('data.button_text')
                ->label('Buton Metni')
                ->minLength(3)
                ->maxLength(200)
                ->visible(fn($get) => $get('data.type') === 'home'),

                TextInput::make('data.button_link')
                ->label('Buton Linki')
                ->minLength(3)
                ->maxLength(200)
                ->visible(fn($get) => $get('data.type') === 'home'),

        ])
        ->collapsible(),
];