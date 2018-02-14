<?php

namespace App\Http\Controllers\Backend\Core\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissions = Permission::orderBy('id', 'asc')->paginate(25);

        return view('core.users.permissions.index', compact('permissions'));
    }
}
