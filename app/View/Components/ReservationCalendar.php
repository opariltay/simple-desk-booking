<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Library\Calendar;

class ReservationCalendar extends Component
{
    public $content;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->content = new Calendar();
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
