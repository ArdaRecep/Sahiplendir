<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\View;
use Filament\Forms\Components\ViewField;
use App\Filament\Resources\PageResource\Pages\CreatePage;
use App\Filament\Resources\PageResource\Pages\EditPage;

use App\Filament\Resources\PageResource\RelationManagers\SectionsRelationManager;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'İçerikler';
    protected static ?string $navigationLabel = 'Sayfalar';
    protected static ?string $pluralLabel = 'Sayfalar';
    protected static ?string $modelLabel = 'Sayfa';

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();
        $data = $record ? self::getRelatedPagesData($record) : [];

        if (!$record) {
            // ⚡️ Create ekranı: eski çoklu-dil formu devam edecek
            return $form
                ->schema([
                    Tabs::make('Diller')
                        ->tabs(self::createLanguageTabs($data))   // <<< burayı düzelttik
                        ->columnSpanFull()
                        ->extraAttributes(['style' => 'border:none;box-shadow:none']),
                ]);
        }

        // ⚙️ Edit ekranı: yeni single-form + diğer diller için redirect/placeholder
        return $form
            ->schema([
                ViewField::make('language_switcher')
                    ->view('filament.forms.language-switcher')
                    ->viewData([
                        'data' => $data,
                        'record' => $record,
                        'routeName' => 'filament.admin.resources.pages.edit',
                    ])
                    ->columnSpanFull(),

                Grid::make()
                    ->columns(2)
                    ->schema(self::renderPageFields($record->language, $data)),
            ])
            ->columns(2);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('İsim'),
                Tables\Columns\TextColumn::make('slug')->label('Slug'),
                Tables\Columns\TextColumn::make('language.name')->label('Dil'),
                Tables\Columns\BooleanColumn::make('is_home')->label('Anasayfa Mı?'),
                Tables\Columns\BooleanColumn::make('published_at')
                    ->label('Aktif Mi?')
                    ->getStateUsing(fn($record) => !is_null($record->published_at))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('language_id')
                    ->label('Dil')
                    ->options([
                        1 => 'Türkçe',
                        2 => 'İngilizce',
                    ])
                    ->default(1)
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    protected static function getRelatedPagesData($model)
    {
        $all_related_pages = Page::where('group_id', $model->group_id)->get();
        $data = [];

        foreach ($all_related_pages as $related_page) {
            $languageCode = Language::find($related_page->language_id)?->code;
            if ($languageCode) {
                $data['pages'][$languageCode] = $related_page;
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
                ->schema(self::renderPageFields($language, $data));
        }

        return $tabs;
    }


    public static function renderPageFields($language, $data)
    {
        return [
            Hidden::make("pages.{$language->code}.language_id")
                ->afterStateHydrated(function ($component) use ($data, $language) {
                    if (isset($data['pages'][$language->code]->language_id)) {
                        $component->state($data['pages'][$language->code]->language_id);
                    }
                }),

            Grid::make()
                ->columns(2)
                ->schema([
                    TextInput::make("pages.{$language->code}.name")
                        ->label(__('Sayfa İsmi'))
                        ->minLength(3)
                        ->maxLength(255)
                        ->afterStateHydrated(function ($component) use ($data, $language) {
                            if (isset($data['pages'][$language->code]->name)) {
                                $component->state($data['pages'][$language->code]->name);
                            }
                        })
                        ->columnSpan(fn ($livewire) => $livewire instanceof CreatePage ? 'full' : 1)
                        ->required(),

                    TextInput::make("pages.{$language->code}.slug")
                        ->label(__("Slug"))
                        ->afterStateHydrated(function ($component) use ($data, $language) {
                            if (isset($data['pages'][$language->code]->slug)) {
                                $component->state($data['pages'][$language->code]->slug);
                            }
                        })->visibleOn("edit"),

                    Toggle::make("pages.{$language->code}.is_home")
                        ->label("Anasayfa Yap")
                        ->afterStateHydrated(function ($component) use ($data, $language) {
                            if (isset($data['pages'][$language->code]->is_home)) {
                                $component->state($data['pages'][$language->code]->is_home);
                            }
                        }),

                    Toggle::make("pages.{$language->code}.is_published")
                        ->label("Yayınla")
                        ->reactive()
                        ->afterStateHydrated(function ($component) use ($data, $language) {
                            if (isset($data['pages'][$language->code]->published_at)) {
                                $component->state(!is_null($data['pages'][$language->code]->published_at));
                            }
                        })
                        ->afterStateUpdated(function ($state, $set) use ($language) {
                            $set("pages.{$language->code}.published_at", $state ? now()->format('Y-m-d H:i:s') : null);
                        })
                        ->dehydrated(false),

                    DateTimePicker::make("pages.{$language->code}.published_at")
                        ->label("Yayınlanma Tarihi")
                        ->reactive()
                        ->format('Y-m-d H:i:s')
                        ->placeholder(__('admin.select_date_time'))
                        ->afterStateHydrated(function ($component) use ($data, $language) {
                            if (!empty($data['pages'][$language->code]?->published_at)) {
                                $component->state($data['pages'][$language->code]->published_at->format('Y-m-d H:i:s'));
                            }
                        })
                        ->columnSpan(2),
                ]),
        ];
    }
}
