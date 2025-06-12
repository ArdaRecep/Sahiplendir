<?php
namespace App\Filament\Resources\TestQuestionResource\RelationManagers;
use App\Filament\Resources\OptionAnimalScoreResource;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\TestQuestion;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Facades\Filament;

class OptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';
    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('option_text')->label('Şık')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('option_text'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Yeni Şık Ekle'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                TableAction::make('scores')
                    ->label('Puanlar')
                    ->icon('heroicon-o-star')
                    ->url(
                        fn($record) =>
                        OptionAnimalScoreResource::getUrl('index')
                        . '?test_option_id=' . $record->id
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
