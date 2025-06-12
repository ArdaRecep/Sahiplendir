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
        ->description(__("admin.manage_section_content", ['section' => 'text-image']))
        ->schema([
            TextInput::make("data.top_title")
                ->label("Üst Başlık"),
            TextInput::make("data.title")
                ->label("Başlık")
                ->required(),
            RichEditor::make("data.content")
                ->label("Metin")
                ->required(),
            Repeater::make("data.items")
                ->label("Maddeler")
                ->addActionLabel("Madde Ekle")
                ->schema([
                    TextInput::make("item")
                        ->label("Madde")
                        ->required(),
                ]),
            TextInput::make("data.btn_text")
                ->label("Buton Metni")
                ->helperText("Buton Metni Girmezseniz Buton Sayfada Gözükmez"),
            TextInput::make("data.btn_link")
                ->label("Buton Linki")
                ->helperText("/dil/sayfa-adi Şeklinde Link Verin. Örn: /tr/hakkimizda"),
            FileUpload::make("data.image")
                ->label("Resim")
                ->required(),
        ])
        ->collapsible(),
];