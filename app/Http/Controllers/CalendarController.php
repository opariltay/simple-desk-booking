<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\Calendar;
use App\Models\Reservation;

class CalendarController extends Controller
{
    public static function get_calendar_content($location_id) {
        $calendar = new Calendar();
        $reservations = Reservation::where('location_id', $location_id)
            ->whereBetween('reservation_date', [$calendar->start_date, $calendar->end_date])->get();

        foreach ($reservations as $reservation) {
            $calendar->add_event($reservation->user->name, $reservation->reservation_date);
        }

        return $calendar;
    }
}
