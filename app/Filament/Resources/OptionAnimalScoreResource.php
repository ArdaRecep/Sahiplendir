<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionAnimalScoreResource\Pages;
use App\Filament\Resources\OptionAnimalScoreResource\RelationManagers;
use App\Models\OptionAnimalScore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class OptionAnimalScoreResource extends Resource
{
    protected static ?string $model = OptionAnimalScore::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
             Select::make('test_option_id')
                ->label('Şık')
                ->relationship('option', 'option_text')
                ->required(),

            Select::make('sub_category_id')
                ->label('Alt Kategori')
                ->relationship('sub_category', 'name')
                ->required(),

            TextInput::make('score')
                ->label('Puan')
                ->numeric()
                ->required()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('option.option_text')->label('Şık'),
            Tables\Columns\TextColumn::make('sub_category.name')->label('Hayvan'),
            Tables\Columns\TextColumn::make('score')->label('Puan'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOptionAnimalScores::route('/'),
            'create' => Pages\CreateOptionAnimalScore::route('/create'),
            'edit' => Pages\EditOptionAnimalScore::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if ($testOptionId = request()->query('test_option_id')) {
            $query->where('test_option_id', $testOptionId);
        }

        return $query;
    }
}
