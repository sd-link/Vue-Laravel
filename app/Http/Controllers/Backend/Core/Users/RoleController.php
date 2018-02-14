<?php

namespace App\Http\Controllers\Backend\Core\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'asc')->paginate(5);

        return view('core.users.roles.index', compact('roles'));
    }
}
