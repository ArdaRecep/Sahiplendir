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
        ->description(__("admin.manage_section_content", ['section' => 'listings']))
        ->schema([
            Select::make('data.type')
                ->options([
                    'home' => 'Anasayfa',
                    'all' => 'Hepsi',
                ])
                ->required()
                ->reactive(),
            TextInput::make("data.title")
                ->label("Başlık")
                ->visible(fn($get) => $get('data.type') === 'home'|| $get('data.type') === 'all'),
            TextInput::make("data.btn_text")
                ->label("Buton Metni")
                ->helperText("Buton Metni Girmezseniz Buton Sayfada Gözükmez")
                ->visible(fn($get) => $get('data.type') === 'home'),
            TextInput::make("data.btn_link")
                ->label("Buton Linki")
                ->helperText("/dil/sayfa-adi Şeklinde Link Verin. Örn: /tr/ilanlar")
                ->visible(fn($get) => $get('data.type') === 'home'),
        ])
        ->collapsible(),
];