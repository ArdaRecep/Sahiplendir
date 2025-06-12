<?php

namespace App\Filament\Resources\ListingResource\Pages;

use App\Filament\Resources\ListingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\ListingApprovedMail;
use App\Mail\ListingDeclinedMail;
use Filament\Forms\Components\Actions\SaveAction;
use Filament\Resources\Pages\EditRecord;

class EditListing extends EditRecord
{
    protected static string $resource = ListingResource::class;
    protected function getFormActions(): array
    {
        return [
             ...parent::getFormActions(),
            // Onayla butonu
            Action::make('approve')
                ->label('Onayla')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn(): bool => $this->record->status === 'inactive')
                ->action(function (): void {
                    $this->record->update(['status' => 'active']);
                    Mail::to($this->record->user->email)
                        ->send(new ListingApprovedMail($this->record));
                    Notification::make()
                        ->title('İlan aktif hale getirildi ve kullanıcı bilgilendirildi.')
                        ->success()
                        ->send();

                    // Listeye dön
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
             Action::make('delete')
                ->label('Reddet')
                ->icon('heroicon-o-trash')
                ->color('danger')
                // Modal başlığı ve onay butonu metni
                ->modalHeading('İlanı Sil ve Reddet')
                ->modalSubheading('Lütfen kullanıcıya iletilecek reddetme sebebini girin.')
                ->modalButton('Gönder')
                // Form alanı: Sebep
                ->form([
                    \Filament\Forms\Components\Textarea::make('reason')
                        ->label('Reddetme Sebebi')
                        ->required()
                        ->rows(3),
                ])
                // Sadece inactive ilanlarda görünsün
                ->visible(fn (): bool => $this->record->status === 'inactive')
                // Action closure, $data içinden reason’u alıyoruz
                ->action(function (array $data): void {
                    // Mail gönderme
                    Mail::to($this->record->user->email)
                        ->send(new ListingDeclinedMail($this->record, $data['reason']));

                    // İlanı sil
                    $this->record->delete();

                    // Bildirim
                    Notification::make()
                        ->title('İlan silindi ve kullanıcı bilgilendirildi.')
                        ->success()
                        ->send();

                    // Listeye dön
                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}
