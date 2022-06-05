<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CandidateRequest;
use App\Models\Candidate;
use App\Models\Vacancy;
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
        CRUD::addColumns([
            [
                'name' => 'name',
                'label' => 'Name'
            ],

            [
                'name' => 'second_name',
                'label' => 'Second name'
            ],

            [
                'name' => 'patronymic',
                'label' => 'Patronymic'
            ],
            [
                'label' => 'Vacancy',
                'type'  => 'select_multiple',
                'name'  => 'vacancy',
                'entity' => 'vacancy',
                'attribute' => 'name',
                'model' => 'App\Model\Vacancy'
            ]
        ]);

        $this->crud->addFilter(
            [
                'name'  => 'vacancy',
                'type'  => 'dropdown',
                'label' => 'vacancy'
            ],
            Vacancy::all()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'vacancy', function ($query) use ($value) {
                    $query->where('vacancy_id', '=', $value);
                });
            }
        );
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

        $this->crud->addField([
                'label'     => 'Vacancy',
                'type'      => 'checklist',
                'name'      => 'vacancy',
                'entity'    => 'vacancy',
                'attribute' => 'name',
                'model'     => "App\Models\Vacancy",
                'pivot'     => true,
            ]
        );

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

    protected function setupShowOperation() {
        $this->setupListOperation();

        $this->crud->addColumns([
            [
                'name' => 'city',
                'label' => 'City'
            ],
            [
                'name' => 'phone_number',
                'label' => 'Phone number'
            ],

            [
                'name' => 'email',
                'label' => 'Email'
            ],

            [
                'name' => 'desired_position',
                'label' => 'Desired position'
            ],

            [
                'name' => 'desired_income',
                'label' => 'Desired income'
            ],

            [
                'name' => 'work_experience',
                'label' => 'Work experience'
            ]
        ]);
    }
}
