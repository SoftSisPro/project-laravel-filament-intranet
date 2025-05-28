<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Filament\Personal\Resources\TimesheetResource;
use App\Imports\MyTimesheet;
use Carbon\Carbon;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        $lastTimesheet = Auth::user()->timesheets()->latest()->first();
        if(!$lastTimesheet){

            return [
                Action::make('inWork')
                    ->label('Enter Work')
                    ->color('success')
                    ->icon('heroicon-s-arrow-right-start-on-rectangle')
                    ->requiresConfirmation()
                    ->action(function (){
                        //- Consultamos user autenticado
                        $user = Auth::user();
                        //- Creamos un nuevo registro de timesheet tipo work
                        $user->timesheets()->create([
                            'calendar_id'=>1,
                            'type'=>'work',
                            'day_in'=>Carbon::now(),
                            'day_out'=>null,
                        ]);
                    }),
                Actions\CreateAction::make()
                    ->icon('heroicon-s-plus-circle'),
            ];
        }

        return [
            Action::make('inWork')
                ->label('Enter Work')
                ->color('success')
                ->keyBindings(['command+1', 'ctrl+1'])
                ->icon('heroicon-s-arrow-right-start-on-rectangle')
                ->visible(!$lastTimesheet->day_out==null)
                ->disabled($lastTimesheet->day_out==null)
                ->requiresConfirmation()
                ->action(function (){
                    //- Consultamos user autenticado
                    $user = Auth::user();
                    //- Creamos un nuevo registro de timesheet tipo work
                    $user->timesheets()->create([
                        'calendar_id'=>1,
                        'type'=>'work',
                        'day_in'=>Carbon::now(),
                        'day_out'=>null,
                    ]);
                    Notification::make()
                        ->title('You have entered to work')
                        ->icon('heroicon-s-arrow-right-start-on-rectangle')
                        ->iconColor('success')
                        ->color('success')
                        ->send();
                }),
            Action::make('stopWork')
                ->label('Stop Work')
                ->keyBindings(['command+o', 'ctrl+o'])
                ->color('success')
                ->icon('heroicon-s-stop')
                ->visible($lastTimesheet->day_out==null && $lastTimesheet->type!='pause')
                ->disabled(!$lastTimesheet->day_out==null)
                ->requiresConfirmation()
                ->action(function () use($lastTimesheet){
                    //- Actualizamos el último registro de timesheet
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();

                    Notification::make()
                        ->title('You have stopped working')
                        ->color('danger')
                        ->icon('heroicon-s-stop')
                        ->iconColor('danger')
                        ->send();
                }),
            Action::make('inPause')
                ->label('Start Pause')
                ->color('info')
                ->icon('heroicon-s-play')
                ->visible($lastTimesheet->day_out==null && $lastTimesheet->type!='pause')
                ->disabled(!$lastTimesheet->day_out==null)
                ->requiresConfirmation()
                ->action(function () use($lastTimesheet){
                    //- Actualizamos el último registro de timesheet
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();
                    //- Creamos un nuevo registro de timesheet tipo pause
                    $user = Auth::user();
                    $user->timesheets()->create([
                        'calendar_id'=>1,
                        'type'=>'pause',
                        'day_in'=>Carbon::now(),
                        'day_out'=>null,
                    ]);

                    Notification::make()
                        ->title('Start your break')
                        ->color('info')
                        ->icon('heroicon-s-play')
                        ->iconColor('info')
                        ->send();
                }),
            Action::make('stopPause')
                ->label('Stop Pause')
                ->color('danger')
                ->icon('heroicon-s-stop')
                ->visible($lastTimesheet->day_out==null && $lastTimesheet->type=='pause')
                ->disabled(!$lastTimesheet->day_out==null)
                ->requiresConfirmation()
                ->action(function () use($lastTimesheet){
                    //- Actualizamos el último registro de timesheet
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();
                    //- Creamos un nuevo registro de timesheet
                    $user = Auth::user();
                    $user->timesheets()->create([
                        'calendar_id'=>1,
                        'type'=>'work',
                        'day_in'=>Carbon::now(),
                        'day_out'=>null,
                    ]);
                    Notification::make()
                        ->title('You start working again')
                        ->color('danger')
                        ->icon('heroicon-s-stop')
                        ->iconColor('danger')
                        ->send();
                }),
            Actions\CreateAction::make()
                ->icon('heroicon-s-plus-circle'),
            ExcelImportAction::make()
                ->color('warning')
                ->label('Importar')
                ->use(MyTimesheet::class)
                ->successNotificationTitle('Importación exitosa')
        ];
    }
}
