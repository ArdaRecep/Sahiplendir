<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ListingResource\Pages;
use App\Filament\Resources\ListingResource\RelationManagers;
use App\Models\Listing;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;
use Symfony\Component\Yaml\Inline;
use Filament\Forms\Components\Actions\Action as FormAction;

class ListingResource extends Resource
{
    protected static ?string $model = Listing::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Kullanıcı')
                    ->relationship('user', 'username')
                    ->required(),
                Select::make('language_id')
                    ->relationship('language', 'name')
                    ->label('Dil')
                    ->required(),
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->reactive()    // değişince alt-kategoriyi tetikle
                    ->required(),
                Select::make('sub_category_id')
                    ->label('Alt Kategori')
                    ->reactive()    // reactive, çünkü options() closure içinde kullanacağız
                    ->required()
                    ->disabled(fn(Get $get) => !$get('category_id'))
                    ->options(
                        fn(Get $get) =>
                        SubCategory::query()
                            ->where('category_id', $get('category_id'))
                            ->pluck('name', 'id')
                    ),
                TextInput::make('title')
                    ->label("Başlık")
                    ->required()
                    ->maxLength(255),
                TextInput::make('data.gender')
                    ->label("Cinsiyet")
                    ->required()
                    ->maxLength(255),
                TextInput::make('data.neutered')
                    ->label("Kısır Mı?")
                    ->required()
                    ->maxLength(255),
                TextInput::make('data.age')
                    ->label("Yaş")
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label("Açıklama")
                    ->required(),  
                FileUpload::make('photos')
                    ->label("Fotoğraflar")
                    ->disk('public')
                    ->directory('all_sections_photo')
                    ->columnSpanFull()
                    ->imageEditor()
                    ->image()
                    ->multiple()
                    ->required()              
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('listing_no')
                    ->label('No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.username')
                    ->label('Üye'),
                Tables\Columns\TextColumn::make('language.name')
                    ->label('Dil'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori'),
                Tables\Columns\TextColumn::make('subCategory.name')
                    ->label('Alt Kategori'),
                Tables\Columns\TextColumn::make('title')->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d.m.Y H:i')
                    ->label('Oluşturma'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'inactive' => 'Pasif',
                        'active' => 'Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListListings::route('/'),
            'create' => Pages\CreateListing::route('/create'),
            'edit' => Pages\EditListing::route('/{record}/edit'),
        ];
    }
}
