<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Language;
use App\Models\Post;
use App\Models\PostCategory;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Filament\Tables\Filters\SelectFilter;

class PostResource extends Resource
{

    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    public static function getnavigationGroup(): string
    {
        return "Blog";
    }

    public static function getLabel(): string
    {
        return "Blog";
    }

    public static function getModelLabel(): string
    {
        return "Blog";
    }

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $isPostEdit = $form->model instanceof Post;
        $data = $isPostEdit ? self::getRelatedPostData($form->model) : [];

        return $form
            ->schema([
                Tabs::make('LanguageTabs')
                    ->tabs(self::createLanguageTabs($data))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("title")
                ->label("Başlık")
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('image')
                ->label("Resim")
                    ->height(150)
                    ->width(150)
                    ->extraImgAttributes(['style' => 'object-fit: contain;'])
            ])
            ->reorderable('order')
            ->defaultSort('order',"asc")
            ->filters([
                SelectFilter::make('language_id')
                ->label("Dil") // Filtre etiketi
                ->options(function () {
                    // Dilleri çekerken baş harflerini büyültüyoruz
                    return Language::pluck('name', 'id')->mapWithKeys(function ($name, $id) {
                        return [$id => Str::title($name)]; // Baş harflerini büyütmek için Str::title kullanılıyor
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
    protected static function getRelatedPostData($model)
    {
        $all_related_posts = Post::where('group_id', $model->group_id)->get();
        $data = [];

        foreach ($all_related_posts as $related_post) {
            $languageCode = Language::find($related_post->language_id)->code;
            $data['post'][$languageCode] = $related_post;
        }

        return $data;
    }

    protected static function createLanguageTabs($data)
    {
        $all_languages = Language::select('id', 'name', 'code')->orderBy('id', 'asc')->get();

        $tabs = [];
        foreach ($all_languages as $language) {
            $tabs[] = Tabs\Tab::make(ucfirst($language->code))
                ->schema(self::renderPostFields($language, $data));
        }

        return $tabs;
    }

    public static function renderPostFields($language, $data)
    {
        return [
            TextInput::make("post.{$language->code}.title")
                ->minLength("3")
                ->label("Title")
                ->maxLength("255")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post'][$language->code])) {
                        $component->state($data['post'][$language->code]->title);
                    }
                })
                ->required(),

            TextInput::make("post.{$language->code}.slug")
                ->minLength("3")
                ->maxLength("255")
                ->visibleOn("edit")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post'][$language->code])) {
                        $component->state($data['post'][$language->code]->slug);
                    }
                })
                ->required(),

            Textarea::make("post.{$language->code}.description")
                ->label("Kısa Özet")
                ->columnSpanFull()
                ->maxLength(250)
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post'][$language->code]->description)) {
                        $component->state($data['post'][$language->code]->description);
                    }
                })
                ->autosize(),

            RichEditor::make("post.{$language->code}.data.content")
                ->minLength(3)
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post'][$language->code]['data']['content'])) {
                        $component->state($data['post'][$language->code]['data']['content']);
                    }
                }),

                FileUpload::make('image')
                ->label('Resim')
                ->disk('public')
                ->directory('posts')
                ->maxSize(1024)
                ->required()
                ->image()
                ->imageEditor(),

            TextInput::make("post.{$language->code}.data.image_alt")
                ->minLength("3")
                ->maxLength("255")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post'][$language->code])) {
                        $component->state($data['post'][$language->code]->data['image_alt']);
                    }
                })
                ->required(),


            Select::make("post.{$language->code}.category")
                ->label('Blog Categories')
                ->options(PostCategory::all()->where('language_id', $language->id)->pluck('title', 'id')->toArray())
                ->searchable()
                ->preload()
                ->multiple()
                ->afterStateHydrated(function (Select $component, $state, $record) use ($language) {
                    if ($record) {
                        $relatedPost = Post::where('group_id', $record->group_id)
                            ->where('language_id', $language->id)
                            ->first();
                        if ($relatedPost) {
                            $component->state($relatedPost->postCategories->pluck('id')->toArray());
                        }
                    }
                })
                ->reactive(),

            DateTimePicker::make("post.{$language->code}.published_at")
                ->label("Yayınlanma Tarihi (BOŞ BIRAKILIRSA POST SİTEDE GÖZÜKMEZ!)")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    if (isset($data['post'][$language->code]) && $data['post'][$language->code]->published_at) {
                        // Format the Carbon date instance to a string that is compatible with the DateTimePicker
                        $formattedDate = $data['post'][$language->code]->published_at->format('Y-m-d H:i:s');
                        $component->state($formattedDate);
                    }
                }),


            Hidden::make("post.{$language->code}.language_id")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['post'][$language->code])) {
                        $component->state($data['post'][$language->code]->language_id);
                    }
                }),
        ];
    }
}
