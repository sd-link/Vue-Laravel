<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Settings\HasSettings;

class Todo extends Model
{
    use HasSettings;

    protected $table = "todos";

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->children()->delete();
        });
   }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function scopeParents($query)
    {
        return $query->where('parent_id', '=', null);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'unique_id')->orderBy('id', 'asc');
    }

    static public function set($projectId, $todoData, $parentId = null)
    {

        $todo = Todo::where('unique_id', $todoData->uniqueId)->where('project_id', $projectId)->first();
        // dump($todo);
        if($todo) {
            $todo->project_id = $projectId;
            $todo->parent_id = $parentId;
            $todo->unique_id = $todoData->uniqueId;
            $todo->type = $todoData->type;
            $todo->order = $todoData->order;
            $todo->update();
        } else {
            $todo = new Todo();
            $todo->project_id = $projectId;
            $todo->parent_id = $parentId;
            $todo->unique_id = $todoData->uniqueId;
            $todo->type = $todoData->type;
            // $todo->order = $todo->order;
            $todo->save();
        }

        return $todo;
    }
}
