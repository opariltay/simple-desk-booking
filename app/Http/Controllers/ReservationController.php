<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Location;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = '';
        $location_id = $request->get('location_id');
        $reservation_date = $request->get('reservation_date');

        if(!empty($location_id) && !empty($reservation_date)) {
            $reservations = Reservation::where('location_id', $location_id)->where('reservation_date', $reservation_date)->get();

            if (count($reservations) > 0) {
                $result .= '<ol class="space-y-1 max-w-md list-decimal list-inside text-gray-500 dark:text-gray-400">';
                foreach ($reservations as $reservation) {
                    $result .= '<li>' . $reservation->user->name . '</li>';
                }
                $result .= '</ol>';
            }
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|numeric|exists:locations,id',
            'reservation_date' => 'required|date|after:yesterday',
        ]);

        $user_id = \Auth::user()->id;
        $location_id = $request->get('location_id');
        $reservation_date = $request->get('reservation_date');

        if ( ReservationController::hasReservation($user_id, $location_id, $reservation_date) ) {
            return redirect('dashboard')->withErrors(__('You already have a reservation for this day!'));
        } else if ( !ReservationController::hasAvailableCapacity($location_id, $reservation_date) ) {
            return redirect('dashboard')->withErrors(__('Location has no available capacity for this day!'));
        }

        $reservation_details = [
            'user_id' => $user_id,
            'location_id' => $location_id,
            'reservation_date' => $reservation_date,
        ];

        Reservation::create($reservation_details);
        return redirect('dashboard')->withSuccess(__('Reservation completed!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'location_id' => 'required|numeric|exists:locations,id',
            'reservation_date' => 'required|date',
        ]);

        $user_id = \Auth::user()->id;
        $location_id = $request->get('location_id');
        $reservation_date = $request->get('reservation_date');

        $reservation = ReservationController::getReservation($user_id, $location_id, $reservation_date);
        
        if (isset($reservation)) {
            $reservation->delete();
            return redirect('dashboard')->withSuccess(__('Reservation canceled!'));
        } else {
            return redirect('dashboard')->withErrors(__('You don\'t have a reservation for this day!'));
        }
    }

    public static function hasReservation($user_id, $location_id, $reservation_date) {
        return ReservationController::getReservation($user_id, $location_id, $reservation_date) != null;
    }

    public static function getReservation($user_id, $location_id, $reservation_date) {
        $reservation = Reservation::where('user_id', $user_id)
            ->where('location_id', $location_id)
            ->where('reservation_date', $reservation_date)->first();

        return isset($reservation) ? $reservation : null;
    }

    public static function hasAvailableCapacity($location_id, $reservation_date, $reservation_id = null) {
        $location = Location::where('id', $location_id)->firstOrFail();
        $reservations = Reservation::where('location_id', $location_id)->where('reservation_date', $reservation_date)->get();

        if ($reservation_id != null) {
            // update mode
            foreach ($reservations as $key => $reservation) {
                if ($reservation->id == $reservation_id) {
                    unset($reservations[$key]);
                }
            }
        }

        $available = $location->capacity - count($reservations);
        return $available > 0;
    }
}
