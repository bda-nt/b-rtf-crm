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
                'label' => 'Имя'
            ],

            [
                'name' => 'second_name',
                'label' => 'Фамилия'
            ],

            [
                'name' => 'patronymic',
                'label' => 'Отчество'
            ],
            [
                'label' => 'Вакансия',
                'type'  => 'select',
                'name'  => 'vacancy',
                'entity' => 'vacancy',
                'attribute' => 'name',
                'model' => 'App\Model\Vacancy'
            ],
            [
                'label' => 'Статус',
                'type' => 'radio',
                'name' => 'status',
                'options' => [
                    '-1' => 'Работа остановлена, отказ',
                    '0' => 'В работе',
                    '1' => 'Работа остановлена, принят',
                ]
            ]
        ]);


        $this->candidateFilters();
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

        $this->baseFields();

        CRUD::field('vacancy_id')->type('select')->model('App\Models\Vacancy')->attribute('name')->entity('vacancy')->label('Вакансия');
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
            'name'=>'status',
            'label' => 'Статус',
            'type' => 'radio',
            'options' => [
                '-1' => 'Остановлен, отклонен',
                '0' => 'В работе',
                '1' => 'Остановлен, принят'
            ]
        ]);
    }

    protected function setupShowOperation() {
        $this->setupListOperation();

        $this->crud->addColumns([
            [
                'name' => 'city',
                'label' => 'Город'
            ],
            [
                'name' => 'phone_number',
                'label' => 'Телефон'
            ],

            [
                'name' => 'email',
                'label' => 'Почта'
            ],

            [
                'name' => 'desired_position',
                'label' => 'Желаемая должность'
            ],

            [
                'name' => 'desired_income',
                'label' => 'Желаемый доход'
            ],

            [
                'name' => 'work_experience',
                'label' => 'Опыт работы'
            ],
        ]);
    }

    /**
     * @return void
     */
    protected function baseFields(): void
    {
        CRUD::addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Имя'
        ]);

        CRUD::addField([
            'name' => 'second_name',
            'type' => 'text',
            'label' => 'Фамилия'
        ]);

        CRUD::addField([
            'name' => 'patronymic',
            'type' => 'text',
            'label' => 'Отчество'
        ]);

        CRUD::addField([
            'name' => 'city',
            'type' => 'text',
            'label' => 'Город'
        ]);

        CRUD::addField([
            'name' => 'phone_number',
            'type' => 'text',
            'label' => 'Телефон'
        ]);

        CRUD::addField([
            'name' => 'email',
            'type' => 'email',
            'label' => 'Почта'
        ]);

        CRUD::addField([
            'name' => 'desired_position',
            'type' => 'text',
            'label' => 'Желаемая должность'
        ]);

        CRUD::addField([
            'name' => 'desired_income',
            'type' => 'number',
            'label' => 'Желаемый доход'
        ]);

        CRUD::addField([
            'name' => 'work_experience',
            'type' => 'text',
            'label' => 'Опыт работы'
        ]);
    }

    /**
     * @return void
     */
    protected function candidateFilters(): void
    {
        $this->crud->addFilter(
            [
                'name' => 'vacancy',
                'type' => 'dropdown',
                'label' => 'Вакансия'
            ],
            Vacancy::all()->pluck('name', 'id')->toArray(),
            function ($value) { // if the filter is active
                $this->crud->addClause('whereHas', 'vacancy', function ($query) use ($value) {
                    $query->where('vacancy_id', '=', $value);
                });
            }
        );

        $this->crud->addFilter(
            [
                'name' => 'status',
                'type' => 'dropdown',
                'label' => 'Статус'
            ],
            [
                -1 => 'Остановлен, отказ',
                0 => 'В работе',
                1 => 'Остановлен, принят',
            ],
            function ($value) {
                $this->crud->addClause('where', 'status', $value);
            }
        );
    }
}
