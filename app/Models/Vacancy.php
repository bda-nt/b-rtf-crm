<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\PermissionRegistrar;

class Vacancy extends Model
{
    use CrudTrait,
        HasFactory;

    protected $table = 'vacancy';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'description', 'responsible_id', 'status', 'author_id'];

    public function author() {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function responsible() {
        return $this->belongsTo(User::class, 'responsible_id', 'id')
            ->withDefault([
                'name'=>'-----'
            ]);
    }

    public function candidate() {
        return $this->hasMany(
            Candidate::class,
            'vacancy_id',
            'id',
        );
    }

}
