<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestQuestionResource\Pages;
use App\Filament\Resources\TestQuestionResource\RelationManagers;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\TestQuestion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\Language;

class TestQuestionResource extends Resource
{
    protected static ?string $model = TestQuestion::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('question_text')->label('Soru')->required(),
                Select::make('language_id')
                ->label('Dil')
                ->relationship('language', 'name')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('question_text')->label('Soru'),
                TextColumn::make('language.name')->label("Dil")
            ])
            ->reorderable('order')
            ->defaultSort('order',"asc")
            ->filters([

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
            RelationManagers\OptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestQuestions::route('/'),
            'create' => Pages\CreateTestQuestion::route('/create'),
            'edit' => Pages\EditTestQuestion::route('/{record}/edit'),
        ];
    }
}
