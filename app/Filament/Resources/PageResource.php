<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use App\Models\Language;
use Carbon\Traits\ToStringFormat;
use Filament\Forms;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'İçerikler';

    public static function form(Form $form): Form
    {
        $current_model = $form->model;
        $isPageEdit = $form->model instanceof Page;
        $data = $isPageEdit ? self::getRelatedPagesData($form->model) : [];

        $tabs = self::createLanguageTabs($data, $current_model);
        $activeTab = $isPageEdit ? $form->model->language_id : 1;

        return $form
            ->schema([
                Tabs::make('Diller')
                    ->tabs($tabs)
                    ->columnSpanFull()
                    ->extraAttributes(['style' => 'border-none !ring-0 !shadow-none'])
                    ->activeTab($activeTab),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug'),
                Tables\Columns\TextColumn::make('language.name')->label('Dil'),
                Tables\Columns\BooleanColumn::make('is_home')->label('Anasayfa Mı?'),
                Tables\Columns\BooleanColumn::make('published_at')
                    ->label('Yayında Mı?')
                    ->getStateUsing(fn($record) => !is_null($record->published_at))
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
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
            $languageCode = Language::find($related_page->language_id)->code;
            $data['pages'][$languageCode] = $related_page;
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
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['pages'][$language->code]->language_id)) {
                        $component->state($data['pages'][$language->code]->language_id);
                    }
                }),

            Grid::make()
                ->columns(2)
                ->schema([
                    TextInput::make("pages.{$language->code}.name")
                        ->label(__('admin.name'))
                        ->prefixIcon('heroicon-o-document', $is_inline = true)
                        ->minLength(3)
                        ->maxLength(255)
                        ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                            if (isset($data['pages'][$language->code]->name)) {
                                $component->state($data['pages'][$language->code]->name);
                            }
                            if (empty($data['pages'][$language->name]->slug)) {
                                $component->columnSpanFull();
                            }
                        })
                        ->required(),

                    TextInput::make("pages.{$language->code}.slug")
                        ->label(__("admin.slug"))
                        ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                            if (isset($data['pages'][$language->code]->slug)) {
                                $component->state($data['pages'][$language->code]->slug);
                            }
                        })
                        ->visibleOn("edit"),

                    Toggle::make("pages.{$language->code}.is_published")
                        ->label(__('admin.is_published'))
                        ->reactive()
                        ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                            if (isset($data['pages'][$language->code]->published_at)) {
                                $component->state(!is_null($data['pages'][$language->code]->published_at));
                            }
                        })
                        ->afterStateUpdated(function ($state, $set) use ($language) {
                            if ($state) {
                                $set("pages.{$language->code}.published_at", now()->format('Y-m-d H:i:s'));
                            } else {
                                $set("pages.{$language->code}.published_at", null);
                            }
                        })
                        ->dehydrated(false),

                    Toggle::make("pages.{$language->code}.is_home")
                        ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                            if (isset($data['pages'][$language->code]->is_home)) {
                                $component->state($data['pages'][$language->code]->is_home);
                            }
                        })
                        ->label(__("admin.is_home_page")),

                    DateTimePicker::make("pages.{$language->code}.published_at")
                        ->label("Yayınlanma Tarihi")
                        ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                            if (isset($data['pages'][$language->code]) && $data['pages'][$language->code]->published_at) {
                                $formattedDate = $data['pages'][$language->code]->published_at->format('Y-m-d H:i:s');
                                $component->state($formattedDate);
                            }
                        })
                        ->columnSpan(2) // 2 sütunluk grid'de tamamı kaplasın
                        ->reactive()
                        ->format('Y-m-d H:i:s')
                        ->placeholder(__('admin.select_date_time'))
                        ->extraAttributes([
                            'onfocus' => 'this.setAttribute("readonly", "readonly"); this.value = "' . now()->format('Y-m-d H:i:s') . '";',
                        ]),
                ])
        ];
    }
}
