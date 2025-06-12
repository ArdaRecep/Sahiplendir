<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostCategoryResource\Pages;
use App\Filament\Resources\PostCategoryResource\RelationManagers;
use App\Models\Language;
use App\Models\PostCategory;
use App\Traits\PostCategoryHelper;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;


class PostCategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getnavigationGroup(): string
    {
        return "Blog";
    }

    public static function getLabel(): string
    {
        return "Blog Kategori";
    }

    public static function getModelLabel(): string
    {
        return "Blog Kategori";
    }
    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        $isPostCategoryEdit = $form->model instanceof PostCategory;
        $data = $isPostCategoryEdit ? self::getRelatedPostCategoryData($form->model) : [];

        return $form
            ->schema([
                Tabs::make('Diller')
                    ->tabs(self::createLanguageTabs($data))
                    ->columnSpanFull(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("title"),
                TextColumn::make("language.name")
                    ->sortable()
                    ->searchable()
                    ->label("Language")
                    ->formatStateUsing(function (string $state): string {
                        return ucfirst($state);
                    }),
            ])
            ->filters([
                SelectFilter::make('language_id')
                ->label("Dil")
                ->options(function () {
                    return Language::pluck('name', 'id')->mapWithKeys(function ($name, $id) {
                        return [$id => Str::title($name)];
                    });
                })
                ->default(function () {
                    return Language::where('is_native', 1)->value('id');
                }),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                ]),
            ]);
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
            'index' => Pages\ListPostCategories::route('/'),
            'create' => Pages\CreatePostCategory::route('/create'),
            'edit' => Pages\EditPostCategory::route('/{record}/edit'),
        ];
    }

    protected static function getRelatedPostCategoryData($model)
    {
        $all_related_post_categories = PostCategory::where('group_id', $model->group_id)->get();
        $data = [];

        foreach ($all_related_post_categories as $related_post_category) {
            $languageCode = Language::find($related_post_category->language_id)->code;
            $data['post_category'][$languageCode] = $related_post_category;
        }

        return $data;
    }

    protected static function createLanguageTabs($data)
    {
        $all_languages = Language::select('id', 'name', 'code')->orderBy('id', 'asc')->get();

        $tabs = [];
        foreach ($all_languages as $language) {
            $tabs[] = Tabs\Tab::make(ucfirst($language->code))
                ->schema(self::renderPostCategoryFields($language, $data));
        }

        return $tabs;
    }

    public static function renderPostCategoryFields($language, $data)
    {
        return [
            TextInput::make("post_category.{$language->code}.title")
                ->minLength("3")
                ->label("Title")
                ->maxLength("255")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post_category'][$language->code])) {
                        $component->state($data['post_category'][$language->code]->title);
                    }
                })
                ->required(),

            TextInput::make("post_category.{$language->code}.slug")
                ->minLength("3")
                ->maxLength("255")
                ->visibleOn("edit")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post_category'][$language->code])) {
                        $component->state($data['post_category'][$language->code]->slug);
                    }
                })
                ->required(),

                DateTimePicker::make("post_category.{$language->code}.published_at")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    if (isset($data['post_category'][$language->code]) && $data['post_category'][$language->code]->published_at) {
                        // Format the Carbon date instance to a string that is compatible with the DateTimePicker
                        $formattedDate = $data['post_category'][$language->code]->published_at->format('Y-m-d H:i:s');
                        $component->state($formattedDate);
                    }
                }),

            Hidden::make("post_category.{$language->code}.language_id")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post_category'][$language->code])) {
                        $component->state($data['post_category'][$language->code]->language_id);
                    }
                }),

        ];
    }
}
