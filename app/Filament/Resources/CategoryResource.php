<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Str;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Filters\SelectFilter;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationGroup = 'İçerik Yönetimi';
    protected static ?string $navigationLabel = 'Kategoriler';
    public static function form(Form $form): Form
    {
        $record = $form->getRecord();
        $data = $record ? self::getRelatedCategoriesData($record) : [];

        return $form
            ->schema([
                Tabs::make('Diller')
                    ->tabs(self::createLanguageTabs($data))
                    ->columnSpanFull()
                    ->extraAttributes(['style' => 'border:none;box-shadow:none']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Ad')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Oluşturma')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('language_id')
                    ->label('Dil')
                    ->options(fn() => Language::all()->pluck('name', 'id')->toArray())
                    ->default(fn() => Language::where('is_native', true)->value('id'))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    protected static function getRelatedCategoriesData($model)
    {
        $all_related_categories = Category::where('group_id', $model->group_id)->get();
        $data = [];

        foreach ($all_related_categories as $related_category) {
            $languageCode = Language::find($related_category->language_id)?->code;
            if ($languageCode) {
                $data['categories'][$languageCode] = $related_category;
            }
        }

        return $data;
    }

    protected static function createLanguageTabs($data)
    {
        $all_languages = Language::select('id', 'name', 'code')->orderBy('id', 'asc')->get();

        $tabs = [];
        foreach ($all_languages as $language) {
            $tabs[] = Tabs\Tab::make(ucfirst($language->code))
                ->schema(self::renderCategoryFields($language, $data));
        }

        return $tabs;
    }

    public static function renderCategoryFields($language, $data)
    {
        return [
            Hidden::make("categories.{$language->code}.language_id")
                ->afterStateHydrated(function ($component) use ($data, $language) {
                    if (isset($data['categories'][$language->code]->language_id)) {
                        $component->state($data['categories'][$language->code]->language_id);
                    }
                }),

            TextInput::make("categories.{$language->code}.name")
                ->label(__('Hayvan Türü'))
                ->minLength(3)
                ->maxLength(255)
                ->afterStateHydrated(function ($component) use ($data, $language) {
                    if (isset($data['categories'][$language->code]->name)) {
                        $component->state($data['categories'][$language->code]->name);
                    }
                })
                ->required(),
        ];
    }
}
