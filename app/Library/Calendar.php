<?php

namespace App\Library;

class Calendar {

    // This calendar class is copied from: https://codeshack.io/event-calendar-php/	

    private $active_year, $active_month, $active_day;
    private $events = [];
    public $start_date, $end_date, $location_id;

    public function __construct($location_id, $date = null) {
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
        $this->start_date = $this->get_calendar_start_date()->toDateString();
        $this->end_date = $this->get_calendar_end_date()->toDateString();
        $this->location_id = $location_id;
    }

    public function add_event($txt, $date, $days = 1, $color = '') {
        $color = $color ? ' ' . $color : $color;
        $this->events[] = [$txt, $date, $days, $color];
    }

    public function get_calendar_start_date() {
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Mon', 1 => 'Tue', 2 => 'Wed', 3 => 'Thu', 4 => 'Fri', 5 => 'Sat', 6 => 'Sun'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);

        $day = $num_days_last_month - $first_day_of_week + 1;
        $now = \Carbon\Carbon::now();
        
        // if any days displayed from last month on the calendar, we take this day as the start date.
        // otherwise, first day of current month is taken as the start date.
        return $first_day_of_week > 0 ? $now->subMonth()->day($day) : $now->day(1);
    }

    public function get_calendar_end_date() {
        return $this->get_calendar_start_date()->addDays(41);
    }

    /**
     * $month
     * -1 = previous month
     * 1 = next month
     * ELSE = current month
     */
    public function calculate_date($month, $day) {
        $result = date('Y-m-d', strtotime($this->active_year . '-' . $this->active_month . '-1'));
        if ($month == -1) {
            $result = date('Y-m-d', strtotime($result . ' -1 month'));
        } elseif ($month == 1) {
            $result = date('Y-m-d', strtotime($result . ' +1 month'));
        }
        return date('Y-m-d', strtotime($result . ' +' . ($day-1) . ' day'));
    }

    public function print_event($month, $day) {
        $result = '';
        $day_compared = $this->calculate_date($month, $day);
        
        foreach ($this->events as $event) {
            for ($d = 0; $d <= ($event[2]-1); $d++) {
                if ($day_compared == date('Y-m-d', strtotime($event[1]))) {
                    $result .= '<div class="event' . $event[3] . '">';
                    $result .= $event[0];
                    $result .= '</div>';
                }
            }
        }

        return $result;
    }

    public function __toString() {
        $num_days = date('t', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year));
        $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($this->active_day . '-' . $this->active_month . '-' . $this->active_year)));
        $days = [0 => 'Mon', 1 => 'Tue', 2 => 'Wed', 3 => 'Thu', 4 => 'Fri', 5 => 'Sat', 6 => 'Sun'];
        $first_day_of_week = array_search(date('D', strtotime($this->active_year . '-' . $this->active_month . '-1')), $days);
        $html = '<div class="calendar">';
        $html .= '<div class="header">';
        $html .= '<div class="month-year">';
        $html .= date('F Y', strtotime($this->active_year . '-' . $this->active_month . '-' . $this->active_day));
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<div class="days">';
        foreach ($days as $day) {
            $html .= '
                <div class="day_name">
                    ' . $day . '
                </div>
            ';
        }
        for ($i = $first_day_of_week; $i > 0; $i--) {
            $n = ($num_days_last_month-$i+1);

            $html .= '<div class="day_num ignore" data-modal-toggle="reservationModal" onclick="updateReservationModal(' . $this->location_id . ', \'' . $this->calculate_date(-1, $n) . '\');">';
            $html .= '<span>' . $n . '</span>';
            $html .= $this->print_event(-1, $n);
            $html .= '</div>';
        }
        for ($i = 1; $i <= $num_days; $i++) {
            $selected = '';
            if ($i == $this->active_day) {
                $selected = ' selected';
            }
            $html .= '<div class="day_num' . $selected . '" data-modal-toggle="reservationModal" onclick="updateReservationModal(' . $this->location_id . ', \'' . $this->calculate_date(0, $i) . '\');">';
            $html .= '<span>' . $i . '</span>';
            $html .= $this->print_event(0, $i);
            $html .= '</div>';
        }
        for ($i = 1; $i <= (42-$num_days-max($first_day_of_week, 0)); $i++) {
            $html .= '<div class="day_num ignore" data-modal-toggle="reservationModal" onclick="updateReservationModal(' . $this->location_id . ', \'' . $this->calculate_date(1, $i) . '\');">';
            $html .= '<span>' . $i . '</span>';
            $html .= $this->print_event(1, $i);
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

}
?>