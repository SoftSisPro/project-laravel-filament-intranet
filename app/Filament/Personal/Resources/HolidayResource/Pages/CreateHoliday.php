<?php

namespace App\Filament\Personal\Resources\HolidayResource\Pages;

use App\Filament\Personal\Resources\HolidayResource;
use App\Mail\HolidayStatus;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CreateHoliday extends CreateRecord
{
    protected static string $resource = HolidayResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::user()->id;
        $data['type'] = 'pending';

        $dataToSend =[
            'day' => $data['day'],
            'name'=> User::find($data['user_id'])->name,
            'action'=> $data['type']
        ];

        //- Send email to admin
        $userAdmin = User::find(1);
        Mail::to($userAdmin)
            ->send(new HolidayStatus($dataToSend, "Solicitud de Vacaciones"));
        //- Notification to user
        $recipient = auth()->user();
        Notification::make()
            ->title('Solicitud de Vacaciones')
            ->body("El dia {$data['day']} esta pendiente de aprovar")
            ->sendToDatabase($recipient);

        return $data;
    }
}
