<?php

namespace App\Filament\Resources\LeaveRequestResource\Pages;

use App\Filament\Resources\LeaveRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateLeaveRequest extends CreateRecord
{
    protected static string $resource = LeaveRequestResource::class;
    protected static ?string $title = 'Ajukan Izin Baru';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('bantuan')
                ->label('Bantuan')
                ->icon('heroicon-o-question-mark-circle')
                ->action(fn () => $this->sendHelpNotification()),
        ];
    }

    protected function sendHelpNotification()
    {
        Notification::make()
            ->info()
            ->title('Bantuan')
            ->body('Silakan hubungi admin jika mengalami kendala saat mengajukan izin.')
            ->send();
    }
}