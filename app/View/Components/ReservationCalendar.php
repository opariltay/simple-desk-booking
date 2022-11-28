<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Library\Calendar;

class ReservationCalendar extends Component
{
    public $content;
    public $location_id;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $calendar = new Calendar();

        // TODO - reservations will be added to the calendar.
        //$calendar->add_event('Holiday', '2022-11-29');
        
        $this->content = $calendar;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.reservation-calendar');
    }
}
