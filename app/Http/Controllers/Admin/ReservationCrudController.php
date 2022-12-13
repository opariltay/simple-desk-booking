<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReservationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReservationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Reservation::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reservation');
        CRUD::setEntityNameStrings('reservation', 'reservations');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('user');
        CRUD::column('location');
        CRUD::column('reservation_date');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'user_id' => 'required|numeric|exists:users,id',
            'location_id' => 'required|numeric|exists:locations,id',
            'reservation_date' => 'required|date|after:yesterday',
        ]);

        $users = \App\Models\User::orderBy('name', 'ASC')->pluck('name', 'id');
        $locations = \App\Models\Location::orderBy('name', 'ASC')->pluck('name', 'id');

        CRUD::addField(
            [
                'name'        => 'user_id',
                'label'       => "User",
                'type'        => 'select_from_array',
                'options'     => $users,
                'allows_null' => false,
            ],
        );
        CRUD::addField(
            [
                'name'        => 'location_id',
                'label'       => "Location",
                'type'        => 'select_from_array',
                'options'     => $locations,
                'allows_null' => false,
            ],
        );
        CRUD::field('reservation_date');
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        $this->autoSetupShowOperation();
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());

        $request = $this->crud->getRequest();
        $user_id = $request->get('user_id');
        $location_id = $request->get('location_id');
        $reservation_date = $request->get('reservation_date');

        $reservation = Reservation::where('user_id', $user_id)
            ->where('location_id', $location_id)
            ->where('reservation_date', $reservation_date)->first();

        // TODO: available capacity for the location must be validated before creating/updating a reservation!

        if ( !isset($reservation) ) {
            $this->crud->unsetValidation(); // validation has already been run
            return $this->traitStore();
        }
        return redirect(config('backpack.base.route_prefix') . '/reservation/create')->withErrors(__('User already has a reservation for this day!'));
    }
}
