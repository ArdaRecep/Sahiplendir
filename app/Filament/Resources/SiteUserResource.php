<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteUserResource\Pages;
use App\Filament\Resources\SiteUserResource\RelationManagers;
use App\Models\SiteUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;

class SiteUserResource extends Resource
{
    protected static ?string $model = SiteUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Kullanıcılar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Section::make('Kullanıcı Bilgileri')
                ->schema([
                    TextInput::make('username')
                        ->label('Kullanıcı Adı')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->alphaDash()
                        ->maxLength(50),

                    TextInput::make('name')
                        ->label('Ad')
                        ->required()
                        ->maxLength(50),

                    TextInput::make('surname')
                        ->label('Soyad')
                        ->required()
                        ->maxLength(50),

                    TextInput::make('email')
                        ->label('E-Posta')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label('Telefon')
                        ->tel()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(20),

                    TextInput::make('password')
                        ->label('Şifre')
                        ->password()
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                        ->required(fn (string $context) => $context === 'create')
                        ->dehydrated(fn ($state) => filled($state)),

                    FileUpload::make('profile_photo')
                        ->label('Profil Fotoğrafı')
                        ->image()
                        ->directory('profile-photos')
                        ->maxSize(1024)
                        ->nullable(),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')->sortable()->searchable()->label('Kullanıcı Adı'),
                 Tables\Columns\TextColumn::make('email')->sortable()->searchable()->label('Email'),
                 Tables\Columns\TextColumn::make('phone')->sortable()->searchable()->label('Telefon'),
                 Tables\Columns\ImageColumn::make('profile_photo')->width(48)->height(48)
                    ->label('Profil Fotoğrafı'),
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
            'index' => Pages\ListSiteUsers::route('/'),
            'create' => Pages\CreateSiteUser::route('/create'),
            'edit' => Pages\EditSiteUser::route('/{record}/edit'),
        ];
    }
}
