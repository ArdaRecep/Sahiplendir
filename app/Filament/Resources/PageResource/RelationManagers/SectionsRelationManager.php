<?php

namespace App\Filament\Resources\PageResource\RelationManagers;

use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ViewField;



class SectionsRelationManager extends RelationManager
{
    public $preview_image_url;
    protected static string $relationship = 'pageSections'; // Page modelindeki ilişki adı

    public static function getnavigationGroup(): string
    {
        return __('Bölümler');
    }

    public static function getLabel(): string
    {
        return __('Bölümler');
    }

    public static function getModelLabel(): string
    {
        return __('Bölümler');
    }

    public function form(Form $form): Form
{
    $form_inputs = [
            TextInput::make("name")
            ->prefixIcon('heroicon-o-view-columns', $is_inline = true)
            ->label("İsim")
            ->required(),

            Select::make('section_id')
                ->label("Bölümler")
                ->options(Section::all()->pluck('name', 'id'))
                ->required()
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state) {
                    // $form parametresi olmadan state güncelleme
                    $section = Section::find($state);
                    $this->preview_image_url = $section && $section->image ? $section->image : null;
                }),
            ViewField::make('preview')
                ->view('filament.forms.preview-image')
                ->visible(fn () => $this->preview_image_url !== null)
                ->columnSpanFull(),
        ];
        // Eğer Form Update içindeyse
        if (!($form->model ===  "App\Models\PageSection")) {
            $sectionName = $form->model->section->slug;

            $filePath = resource_path("views/sections/admin/{$sectionName}.php");

            if (file_exists($filePath)) {
                require_once($filePath);

                // $update_form_inputs değişkenine erişim
                if (isset($update_form_inputs)) {
                    $form_inputs = $update_form_inputs;
                } else {
                    dd('Değişken $update_form_inputs dosyada tanımlanmamış.');
                }
            } else {
                dd("Dosya bulunamadı: {$filePath}");
            }
        }

        return $form
            ->schema($form_inputs)->columns(2);
}


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Ad'),
                Tables\Columns\TextColumn::make('section.name')->label('Section Başlığı'),
                Tables\Columns\ImageColumn::make('section.image')->label('Önizleme Görseli')->width(100)->height(100)
                ->extraImgAttributes(['style' => 'object-fit: contain;']),
            ])
            ->reorderable('order')
            ->defaultSort('order',"asc")
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    // Kaydedildikten sonra çalışacak method
    public static function afterSave($record)
    {
        // Pivot kaydını güncellemek için
        $record->sections()->updateExistingPivot($record->section_id, ['name' => $record->name]);
    }
}
