<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = "projects";

    public function todos()
    {
        return $this->hasMany(Todo::class, 'todo_id', 'id');
    }
}
