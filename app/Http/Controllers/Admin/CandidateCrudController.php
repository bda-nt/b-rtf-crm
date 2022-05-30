<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CandidateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CandidateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CandidateCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
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
        CRUD::setModel(\App\Models\Candidate::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/candidate');
        CRUD::setEntityNameStrings('candidate', 'candidate');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Name'
        ]);

        CRUD::addColumn([
            'name' => 'second_name',
            'label' => 'Second name'
        ]);

        CRUD::addColumn([
            'name' => 'patronymic',
            'label' => 'Patronymic'
        ]);

        CRUD::addColumn([
            'name' => 'city',
            'label' => 'City'
        ]);

        CRUD::addColumn([
            'name' => 'phone_number',
            'label' => 'Phone number'
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'label' => 'Email'
        ]);

        CRUD::addColumn([
            'name' => 'desired_position',
            'label' => 'Desired position'
        ]);

        CRUD::addColumn([
            'name' => 'desired_income',
            'label' => 'Desired income'
        ]);

        CRUD::addColumn([
            'name' => 'work_experience',
            'label' => 'Work experience'
        ]);


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CandidateRequest::class);

        CRUD::addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
        ]);

        CRUD::addField([
            'name' => 'second_name',
            'type' => 'text',
            'label' => 'Second name'
        ]);

        CRUD::addField([
            'name' => 'patronymic',
            'type' => 'text',
            'label' => 'Patronymic'
        ]);

        CRUD::addField([
            'name' => 'city',
            'type' => 'text',
            'label' => 'City'
        ]);

        CRUD::addField([
            'name' => 'phone_number',
            'type' => 'text',
            'label' => 'Phone number'
        ]);

        CRUD::addField([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Email'
        ]);

        CRUD::addField([
            'name' => 'desired_position',
            'type' => 'text',
            'label' => 'Desired position'
        ]);

        CRUD::addField([
            'name' => 'desired_income',
            'type' => 'number',
            'label' => 'Desired income'
        ]);

        CRUD::addField([
            'name' => 'work_experience',
            'type' => 'text',
            'label' => 'Work experience'
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
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
}
