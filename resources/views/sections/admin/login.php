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
        ->description(__("admin.manage_section_content", ['section' => 'login']))
        ->schema([
            //
        ])
        ->collapsible(),
];