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
        ->description(__("admin.manage_section_content", ['section' => 'slider']))
        ->schema([
            Repeater::make("data.slider")
                ->schema([
                    FileUpload::make("image")
                        ->label("Resim:")
                        ->disk('public')
                        ->directory('all_sections_photo')
                        ->maxSize(2048)
                        ->image()
                        ->imageEditor()
                        ->columnSpanFull()
                        ->required(),
                    TextInput::make("title")
                        ->label("Başlık:")
                        ->required(),
                    TextInput::make("text")
                        ->label("Metin:")
                        ->required(),
                    TextInput::make("btn_text")
                        ->label("Buton Metni:")
                        ->required(),
                    TextInput::make("btn_link")
                        ->label("Buton Linki:")
                        ->required(),
                ])
                ->label("Slider")
                ->required()
                ->addActionLabel("Item ekle"),
        ])
        ->collapsible(),
];