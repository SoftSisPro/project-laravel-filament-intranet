<?php

namespace App\Imports;

use App\Models\Calendar;
use App\Models\Timesheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use EightyNine\ExcelImport\EnhancedDefaultImport;

class MyTimesheet implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $caledar = Calendar::where('name', $row['calendario'])->first();
            if ($caledar) {
                Timesheet::create([
                    'user_id' => Auth::user()->id,
                    'calendar_id' => $caledar->id,
                    'type' => $row['tipo'],
                    'day_in' => $row['desde'],
                    'day_out' => $row['hasta'],
                ]);
            }
        }
    }
}
