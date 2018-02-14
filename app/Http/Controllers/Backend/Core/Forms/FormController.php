<?php

namespace App\Http\Controllers\Backend\Core\Forms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Forms\Form;
use App\Models\Core\Forms\Field;
use Session;

class FormController extends Controller
{
    public function index()
    {
        $forms = Form::latest()->get();
        return view('core.forms.index', compact('forms'));
    }

    public function create()
    {
        return view('core.forms.create');
    }

    public function edit($id)
    {
        $form = Form::where('id', $id)->first();
        // dd($form->submitter_message);
        return view('core.forms.edit', compact('form'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:160|min:3',
        ]);

        $form = new Form();

        $form->title = $request->title;
        $form->meta = $request->meta;
        $form->save();

        if(isset($request->meta['fields'])) {
            foreach ($request->meta['fields'] as $key => $incommingField) {
                $field = new Field();
                if($incommingField['type'] == 'select') {
                    if(isset($incommingField['selectvalues'])) {
                        $meta = [];
                        $meta['selectvalues'] = $incommingField['selectvalues'];
                        $field->meta = $meta;
                    } else {
                        $meta = [];
                        $meta['selectvalues'] = [];
                        $field->meta = $meta;
                    }
                }

                $field->form_id = $form->id;
                $field->field_id = $incommingField['field_id'];
                $field->group = $incommingField['group'];
                $field->type = $incommingField['type'];
                $field->label = $incommingField['label'];
                $field->placeholder = $incommingField['placeholder'];
                $field->order = ($key + 1);
                $field->required = filter_var($incommingField['required'], FILTER_VALIDATE_BOOLEAN);
                $field->save();
            }
        }

        if($form) {
            Session::put('form-created', 'true');
            return response()->json([
                'status' => 'success',
                'form' => $form,
            ], 201);
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:160|min:3',
        ]);

        $form = Form::find($request->form_id);

        $form->title = $request->title;

        // dd($request->meta['fields']);

        // get all existing fields connected to this form
        $formFieldsIds = $form->fields()->orderBy('order')->get()->pluck('id')->toArray();
        $incommingFieldIds = [];

        if(isset($request->meta['fields'])) {
            foreach ($request->meta['fields'] as $key => $incommingField) {
                if(isset($incommingField['db_id'])) {
                    array_push($incommingFieldIds, $incommingField['db_id']);
                    $field = Field::where('id', $incommingField['db_id'])->first();
                } else {
                    $field = new Field();
                }

                if($incommingField['type'] == 'select') {
                    if(isset($incommingField['selectvalues'])) {
                        $meta = [];
                        $meta = $field->meta;
                        $meta['selectvalues'] = $incommingField['selectvalues'];
                        $field->meta = $meta;
                    } else {
                        $meta['selectvalues'] = [];
                        $field->meta = $meta;
                    }

                }

                $field->form_id = $form->id;
                $field->field_id = $incommingField['field_id'];
                $field->group = $incommingField['group'];
                $field->type = $incommingField['type'];
                $field->label = $incommingField['label'];
                $field->placeholder = $incommingField['placeholder'];
                $field->order = ($key + 1);
                $field->required = filter_var($incommingField['required'], FILTER_VALIDATE_BOOLEAN);
                $field->save();
            }

            $removedFields = array_diff($formFieldsIds, $incommingFieldIds);

            foreach ($removedFields as $key => $id) {
                $field = Field::where('id', $id)->first();
                $field->delete();
            }

        } else {
            $form->fields()->delete();
        }

        $form->meta = $request->meta;

        $form->save();
        $fields = $form->fields()->orderBy('order')->get();

        if($form) {
            return response()->json([
                'status' => 'success',
                'form' => $form,
                'fields' => $fields
            ], 201);
        }

    }

}
