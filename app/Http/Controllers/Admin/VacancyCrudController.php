<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VacancyRequest;
use App\Models\Candidate;
use App\Models\User;
use App\Models\Vacancy;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Http\Controllers\Admin\CandidateCrudController;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation { show as traitShow; }


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
        /**$this->crud->setShowView('vacancies.show');*/
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->baseColumns();

        $this->crud->query->withCount('candidate');

        $this->crud->addColumn([
            'label'     => 'Кандидаты',
            'type'      => 'text',
            'name'      => 'candidate_count',
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('candidate?vacancy='.$entry->getKey());
                },
            ],
            'suffix'    => ' '.strtolower('Кандидатов'),
        ]);

        $this->vacancyFilters();
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(VacancyRequest::class);
        $user = backpack_user();
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => 'Название'
            ],
            [
                'name' => 'description',
                'label' => 'Описание'
            ]
        ]);
        $this->crud->field('status')->type('hidden')->default('0');
        $this->crud->field('author_id')->type('hidden')->default($user->id);
    }

    protected function setupUpdateOperation()
    {
        $this->availableForms();

    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        $this->crud->addColumn([
            'name'=>'reason',
            'label'=>'Причина',
            'type'=>'text'
        ]);
    }

    /**
     * @return void
     */
    protected function baseColumns(): void
    {
        $this->crud->addColumn([
            'name' => 'name',
            'label' => 'Название'
        ]);

        $this->crud->addColumn([
            'label' => 'Автор',
            'type' => 'select',
            'name' => 'author_id',
            'entity' => 'author',
            'attribute' => 'name',
            'model' => 'App\Models\User'
        ]);

        $this->crud->addColumn([
            'label' => 'Ответсвенный',
            'type' => 'select',
            'name' => 'responsible_id',
            'entity' => 'responsible',
            'attribute' => 'name',
            'model' => 'App\Models\User'
        ]);

        $this->crud->addColumn([
            'name' => 'status',
            'label' => 'Статус',
            'type' => 'radio',
            'options' => [
                -1 => 'Отклонена',
                0 => 'Ожидает решения',
                1 => 'Ожидает ответсвенного',
                2 => 'В работе',
                4 => 'Закрыта',
            ]
        ]);
    }

    /**
     * @return void
     */
    protected function vacancyFilters(): void
    {
        $this->crud->addFilter(
            [
                'name' => 'status',
                'type' => 'dropdown',
                'label' => 'Статус'
            ],
            [
                -1 => 'Отклонена',
                0 => 'Ожидает решения',
                1 => 'Ожидает ответсвенного',
                2 => 'В работе',
                4 => 'Закрыта',
            ],
            function ($value) {
                $this->crud->addClause('where', 'status', $value);
            }
        );

        $this->crud->addFilter(
            [
                'name' => 'responsible',
                'type' => 'select2',
                'label' => 'Ответсвенный',
            ],
            User::all()->pluck('name', 'id')->toArray(),
            function ($value) {
                $this->crud->addClause('where', 'responsible_id', $value);
            }
        );

        $this->crud->addFilter(
            [
                'name' => 'author',
                'type' => 'select2',
                'label' => 'Автор',
            ],
            User::all()->pluck('name', 'id')->toArray(),
            function ($value) {
                $this->crud->addClause('where', 'author_id', $value);
            }
        );
    }

    /**
     * @return void
     */
    protected function availableForms(): void
    {
        if (backpack_user()->hasRole('superiors')) {
            $this->crud->addField([
                'name' => 'status',
                'label' => 'Статус',
                'type' => 'radio',
                'options' => [
                    '-1' => "Отказ",
                    '1' => "Принято, ожидает ответсвенного",
                ]
            ]);

            $this->crud->addField([
                'name' => 'reason',
                'label' => 'Причина (если выбран отказ)'
            ]);
        } else if (backpack_user()->hasRole('superiors_department')) {
            $this->crud->addField([
                'name' => 'status',
                'label' => 'Status',
                'type' => 'radio',
                'options' => [
                    '2' => "In work with candidate",
                ]
            ]);

            $this->crud->addField([
                'label' => 'Responsible',
                'type' => 'select2_grouped',
                'name' => 'responsible_id',
                'entity' => 'responsible',
                'attribute' => 'name',
                'group_by' => 'roles',
                'group_by_attribute' => 'name',
                'group_by_relationship_back' => 'users',
            ]);
        }
    }
}
