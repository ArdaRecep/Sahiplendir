<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Language;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;



class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    public static function getNavigationUrl(): string
    {
        $siteSetting = SiteSetting::first();

        if ($siteSetting) {
            return static::getUrl('edit', ['record' => $siteSetting]);
        }

        return static::getUrl();
    }
    protected static ?string $navigationIcon = 'heroicon-o-wrench';
    protected static ?string $navigationGroup = 'Ayarlar';
    protected static ?string $navigationLabel = 'Site Ayarları';

    public static function form(Form $form): Form
    {
        $isSiteSettingEdit = $form->model instanceof SiteSetting;
        $data = $isSiteSettingEdit ? self::getRelatedSiteSettingsData($form->model) : [];

        $all_languages = Language::select('id', 'code')->orderBy('id', 'asc')->get();
        $native_lang = Language::where('is_native',1)->get('id');
        // Aktif sekmeyi belirlemek için
        if(isset($form->model->language_id) && isset($native_lang[0]->id))
            $activeTabIndex = $native_lang[0]->id;
        

        return $form
            ->schema([
                Tabs::make('LanguageTabs')
                    ->tabs(self::createLanguageTabs($data, $all_languages))
                    ->activeTab($activeTabIndex ?? 1)
                    ->columnSpanFull(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    protected static function getRelatedSiteSettingsData($model)
    {
        $all_related_site_settings = SiteSetting::where('group_id', $model->group_id)->get();
        $data = [];

        foreach ($all_related_site_settings as $related_site_setting) {
            $languageName = Language::find($related_site_setting->language_id)->code;
            $data['site_settings'][$languageName] = $related_site_setting;
        }

        return $data;
    }
    protected static function createLanguageTabs($data, $all_languages)
    {
        $tabs = [];
        foreach ($all_languages as $language) {
            $tabs[] = Tabs\Tab::make(ucfirst($language->code))
                ->schema(self::renderSiteSettingFields($language, $data));
        }

        return $tabs;
    }




    public static function renderSiteSettingFields($language, $data)
    {
        return [
            TextInput::make("site_settings.{$language->code}.data.name")
                ->label(__('Site İsmi'))
                ->minLength(2)
                ->maxLength(255)
                ->columnSpanFull()
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['site_settings'][$language->code])) {
                        $component->state($data['site_settings'][$language->code]['data']['name']);
                    }
                }),



            Hidden::make("site_settings.{$language->code}.language_id")
                ->afterStateHydrated(function ($component, $state) use ($data, $language) {
                    // Ensure that the page exists for this language
                    if (isset($data['site_settings'][$language->code])) {
                        $component->state($data['site_settings'][$language->code]->language_id);
                    }
                }),

            Section::make("Logolar")
                ->schema([
                    FileUpload::make('footer_logo')
                        ->label('Logo')
                        ->disk('public')
                        ->directory('site_settings')
                        ->maxSize(3072)
                        ->image()
                        ->imageEditor(),
                        
                    FileUpload::make('logo')
                        ->label('Footer Logo')
                        ->disk('public')
                        ->directory('site_settings')
                        ->maxSize(3072)
                        ->image()
                        ->imageEditor(),

                    FileUpload::make('fav_icon')
                        ->label('Fav Icon')
                        ->disk('public')
                        ->directory('site_settings')
                        ->maxSize(3072)
                        ->image()
                        ->imageEditor(),
                ])
                ->collapsed()
                ->columns(1),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
