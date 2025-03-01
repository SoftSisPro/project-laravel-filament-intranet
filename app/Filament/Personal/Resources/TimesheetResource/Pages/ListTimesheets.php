<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Filament\Personal\Resources\TimesheetResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('inWork')
                ->label('Enter Work')
                ->color('success')
                ->icon('heroicon-s-arrow-right-start-on-rectangle')
                ->requiresConfirmation()
                ->action(function (){
                    $user = Auth::user();
                    $user->timesheets()->create([
                        'calendar_id'=>1,
                        'type'=>'work',
                        'day_in'=>Carbon::now(),
                        'day_out'=>null,
                    ]);
                }),
            Action::make('inPause')
                ->label('Start Pause')
                ->color('info')
                ->icon('heroicon-s-play')
                ->requiresConfirmation(),
            Actions\CreateAction::make()
                ->icon('heroicon-s-plus-circle'),
        ];
    }
}
