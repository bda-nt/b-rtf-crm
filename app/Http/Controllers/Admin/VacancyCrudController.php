<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VacancyRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class VacancyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VacancyCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Vacancy::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/vacancy');
        CRUD::setEntityNameStrings('vacancy', 'vacancies');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            'name'=>'name',
            'label'=>'Name'
        ]);

        $this->crud->addColumn([
            'label'=>'Author',
            'type'=>'select',
            'name'=>'author_id',
            'entity'=>'author',
            'attribute'=>'name',
            'model'=>'App\Models\User'
        ]);

        $this->crud->addColumn([
            'label'=>'Responsible',
            'type'=>'select',
            'name'=>'responsible_id',
            'entity'=>'responsible',
            'attribute'=>'name',
            'model'=>'App\Models\User'
        ]);

        $this->crud->addColumn([
            'name'        => 'status',
            'label'       => 'Status',
            'type'        => 'radio',
            'options'     => [
                'begin' => 'On accepted',
                'stop' => 'Closed, check reason',
                ''
            ]
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
        CRUD::setValidation(VacancyRequest::class);
        $user = backpack_user();
        $this->crud->addFields(['name', 'description']);
        $this->crud->field('status')->type('hidden')->default('begin');
        $this->crud->field('author_id')->type('hidden')->default($user->id);

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
        $this->crud->addField([
            'name'        => 'status', // the name of the db column
            'label'       => 'Status', // the input label
            'type'        => 'radio',
            'options'     => [
                'stop'=> "Vacancy stopped",
                'start' => "Accepted, waiting responsible",
                'in_work'=> "In work",
                'end'=>'Vacancy closed'
            ]
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        $this->crud->addColumn([
            'name'=>'reason',
            'label'=>'Reason',
            'type'=>'text'
        ]);
    }
}
