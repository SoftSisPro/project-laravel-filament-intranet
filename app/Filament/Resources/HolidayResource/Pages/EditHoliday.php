<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Mail\HolidayStatus;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        //- Data user
        $user = User::find($record->user_id);
        $dataSend = [
            'name' => $user->name,
            'day' => $record->day,
            'action' => $record->type
        ];
        //- Send email to only approved
        if ($record->type == 'approved') {
            Mail::to($user)
                ->send(new HolidayStatus($dataSend, "Vacaciones Aprobadas"));
            //- Notification to user
            $recipient = $user;
            Notification::make()
                ->title('Estado de la Solicitud de Vacaciones')
                ->body("El dia {$data['day']} esta aprovado")
                ->sendToDatabase($recipient);
        }
        //- Send email to only declined
        else if($record->type == 'decline'){
            Mail::to($user)
                ->send(new HolidayStatus($dataSend, "Vacaciones Rechazadas"));
            //- Notification to user
            $recipient = $user;
            Notification::make()
                ->title('Estado de la Solicitud de Vacaciones')
                ->body("El dia {$data['day']} esta rechazado")
                ->sendToDatabase($recipient);
        }
        return $record;
    }


}
