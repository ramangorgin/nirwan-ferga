<?php

namespace App\Services\DateTime;

use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;
use Illuminate\Validation\ValidationException;

class DateTimeService
{
    /**
     * Convert Jalali datetime string to UTC Carbon.
     * Input is interpreted in user's timezone.
     */
    public function jalaliToUtc(string $jalaliDateTime, string $userTimezone): Carbon
    {
        try {
            // Convert Jalali to Gregorian string first
            $gregorian = Verta::parse($jalaliDateTime)->formatGregorian('Y-m-d H:i:s');

            // Create Carbon in user's TZ, then convert to UTC for storage
            return Carbon::createFromFormat('Y-m-d H:i:s', $gregorian, $userTimezone)->utc();
        } catch (\Throwable $e) {
            throw ValidationException::withMessages([
                'datetime' => ['فرمت تاریخ/زمان نامعتبر است.'],
            ]);
        }
    }

    /**
     * Convert Jalali date + local time to UTC parts for storing in separate date/time columns.
     * Returns: ['date' => 'Y-m-d', 'time' => 'H:i:s', 'carbon' => Carbon(UTC)]
     */
    public function jalaliDateAndTimeToUtcParts(string $jalaliDate, string $time, string $userTimezone): array
    {
        try {
            // Convert Jalali date to Gregorian date string
            $gregorianDate = \Hekmatinasser\Verta\Verta::parse($jalaliDate)->formatGregorian('Y-m-d');

            // Build a local datetime string then convert to UTC
            $local = \Carbon\Carbon::createFromFormat('Y-m-d H:i', "{$gregorianDate} {$time}", $userTimezone);
            $utc = $local->clone()->utc();

            return [
                'date' => $utc->format('Y-m-d'),
                'time' => $utc->format('H:i:s'),
                'carbon' => $utc,
            ];
        } catch (\Throwable $e) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'datetime' => ['Invalid date/time format.'],
            ]);
        }
    }
    public function jalaliDateToGregorian(string $jalaliDate): string
    {
        // returns 'Y-m-d'
        return \Hekmatinasser\Verta\Verta::parse($jalaliDate)->formatGregorian('Y-m-d');
    }
}
