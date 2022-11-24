{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> Users</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('location') }}"><i class="nav-icon la la-building"></i> Locations</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('reservation') }}"><i class="nav-icon la la-calendar-check"></i> Reservations</a></li>