<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use CrudTrait;

    protected $table = 'candidate';
    protected $guarded = ['id'];

    public function vacancy()
    {
        return $this->belongsTo(
            Vacancy::class,
            'vacancy_id',
        );
    }
}
