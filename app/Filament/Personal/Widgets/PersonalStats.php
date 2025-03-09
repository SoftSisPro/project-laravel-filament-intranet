<?php

namespace App\Filament\Personal\Widgets;

use App\Models\Holiday;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PersonalStats extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        return [
            Stat::make('Pending Holidays', $this->getPendingHoliday($user)),
            Stat::make('Approved Holidays', $this->getApprovedHoliday($user)),
            Stat::make('Total Work', $this->getTotalWork($user)),
            Stat::make('Total Pause', $this->getTotalPause($user)),
        ];
    }

    protected function getPendingHoliday(User $user)
    {
        $total = Holiday::where('user_id', $user->id)
            ->where('type', 'pending')->get()->count();

        return $total;
    }

    protected function getApprovedHoliday(User $user)
    {
        $total = Holiday::where('user_id', $user->id)
            ->where('type', 'approved')->get()->count();

        return $total;
    }

    protected function getTotalWork(User $user)
    {
        $timesheets = $user->timesheets()
            ->where('type', 'work')
            ->whereDate('created_at', Carbon::today())
            ->get();
        $sumHours = 0;
        foreach ($timesheets as $timesheet) {
            $dayIn = Carbon::parse($timesheet->day_in);
            $dayOut = Carbon::parse($timesheet->day_out);
            $diff = $dayIn->diffInSeconds($dayOut);
            $sumHours += $diff;
        }
        $tiempoFormato = gmdate('H:i:s', $sumHours);
        return $tiempoFormato;
    }

    protected function getTotalPause(User $user)
    {
        $timesheets = $user->timesheets()
            ->where('type', 'pause')
            ->whereDate('created_at', Carbon::today())
            ->get();
        $sumHours = 0;
        foreach ($timesheets as $timesheet) {
            $dayIn = Carbon::parse($timesheet->day_in);
            $dayOut = Carbon::parse($timesheet->day_out);
            $diff = $dayIn->diffInSeconds($dayOut);
            $sumHours += $diff;
        }
        $tiempoFormato = gmdate('H:i:s', $sumHours);
        return $tiempoFormato;
    }
}
