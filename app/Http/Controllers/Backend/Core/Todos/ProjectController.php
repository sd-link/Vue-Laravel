<?php

namespace App\Http\Controllers\Backend\Core\Todos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Content\ContentType;
use App\Models\Core\Project;
use App\Models\Core\Todo;

use DB;
use Session;
use Auth;
use Timezone;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::latest()->paginate(10);

        return view('core.content.projects.index', compact('projects'));
    }

    public function todos($id)
    {
        // $todos = Todo::with(array('children.children.settings', 'children.settings'))->with('settings')->where('project_id', 1)->parents()->get();
        $todos = Todo::with('settings')->where('project_id', 1)->orderBy('id', 'asc')->get();

        // foreach ($template->todos as $key => $block) {
        //     $block->settings;
        // }

        return response()->json([
            'status' => 'success',
            'todos' => $todos
        ], 201);
    }

    public function create()
    {
        return view('core.projects.create');
    }

    public function edit($id)
    {
        $project = Project::where('id', 1)->first();
        // $todos = $template->todos();

        return view('core.projects.edit', compact('project'));
    }

    public function deleteTodo(Request $request)
    {
        $todo = Todo::where('id', $request->id)->first();

        $todo->delete();

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    public function save(Request $request)
    {
        $todosData = $request->todosData;

        for ($i=0; $i < count($todosData); $i++) {
            $this->createOrUpdateTodo(1, $todosData[$i]);
        }

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    protected function createOrUpdateTodo($project_id, $todoData, $parent = null)
    {
        $todoData = (object) $todoData;
        $todo = Todo::set($project_id, $todoData, $parent);

        foreach ($todoData->settings as $key => $setting) {
            $todo->setSetting($key, $setting['value'], $setting['type']);
        }
        if(isset($todoData->subTodos))
            for ($i=0; $i < count($todoData->subTodos); $i++) {
                $this->createOrUpdateTodo($project_id, $todoData->subTodos[$i], $todo->unique_id);
            }
    }
}
