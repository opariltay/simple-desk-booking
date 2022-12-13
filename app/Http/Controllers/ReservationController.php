<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|numeric|exists:locations,id',
            'reservation_date' => 'required|date|after:yesterday',
        ]);

        // TODO: available capacity for the location must be validated before creating/updating a reservation!
        
        $user = \Auth::user();
        $location_id = $request->get('location_id');
        $reservation_date = $request->get('reservation_date');

        $reservation = Reservation::where('user_id', $user->id)
            ->where('location_id', $location_id)
            ->where('reservation_date', $reservation_date)->first();

        if ( !isset($reservation) ) {
            $reservation_details = [
                'user_id' => $user->id,
                'location_id' => $location_id,
                'reservation_date' => $reservation_date,
            ];

            Reservation::create($reservation_details);
        }

        return redirect('dashboard');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    public function getReservationList(Request $request) {
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
}
