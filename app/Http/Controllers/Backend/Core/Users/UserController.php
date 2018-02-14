<?php

namespace App\Http\Controllers\Backend\Core\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;

use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use Session;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $sorting = $request->sorting;
        $order = $request->order;
        $search = $request->search;
        $filter = $request->filter;


        $append = array();
        \DB::connection()->enableQueryLog();
        $users = User::where('id', '!=', 0);

        if($sorting){
            switch ($sorting) {
                case 'id':
                    $users->orderBy('id', $order);
                break;

                case 'name':
                    $users->orderBy('firstname', $order);
                break;

                case 'username':
                    $users->orderBy('username', $order);
                break;

                case 'email':
                    $users->orderBy('email', $order);
                break;

                default:
                    # code...
                break;
            }
            $append += array('sorting' => $sorting);
            $append += array('order' => $order);
        }

        if($search){
            switch ($filter) {
                case 'name':
                    $users->where('firstname', 'LIKE', '%'. $search . '%')->orWhere('lastname', 'LIKE', '%'. $search . '%');
                break;

                case 'username':
                    $users->where('username', 'LIKE', '%'. $search . '%');
                break;

                case 'email':
                    $users->where('email', 'LIKE', '%'. $search . '%');
                break;

                case 'role':
                    $users->whereRoleIs($search);
                break;

                default:
                    # code...
                break;
            }
            $append += array('search' => $search);
            $append += array('filter' => $filter);
        }

        $users = $users->paginate(15);
        $users->appends($append);
        $query = \DB::getQueryLog();
        // echo "currentPage: " . $users->currentPage();
        // echo "<br>lastPage: " . $users->lastPage();
        // echo "<br>total: " . $users->total();
        if($users->currentPage() > $users->lastPage() && !$users->total() && $users->currentPage() > 1) {
            $redirectTo = route('backend.users.index') . "?page=".$users->lastPage();
            return redirect($redirectTo);
        } else if($users->currentPage() != 1 && !$users->count()) {
            return redirect($users->url(1));
        }

        $returnedUsersCount = $users->total();

        return view('core.users.index', compact('users', 'returnedUsersCount', 'order', 'sorting', 'search', 'filter'));
    }

    public function create(Request $request)
    {
        if($request->ajax()) {

            $messages = [
                'password.regex' => 'Password needs to have 1 lowercase, 1 upercase and 1 Non-alphanumeric character.',
                'roles.required' => 'Atleast one role must be assigned.',
            ];

            $validator = Validator::make($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20',
                'email' => 'required|email|unique:users,email,',
                'bio' => 'sometimes|max:3',
                // 'password' => 'sometimes|min:6|confirmed',
                'password' => 'bail|required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|min:6|confirmed',
                'roles' => 'required|array',
                'permissions' => 'sometimes|array',
                ],
                $messages
            );

            $validator->sometimes('firstname', 'alpha|min:3|max:20', function ($request) {
                if($request->firstname != '')
                    return true;
                else {
                    return false;
                }
            });

            $validator->sometimes('lastname', 'alpha|min:3|max:20', function ($request) {
                if($request->lastname != '')
                    return true;
                else {
                    return false;
                }
            });

            $validator->validate();

            $user = new User();

            $user->username = $request->username;
            $user->email = $request->email;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->bio = $request->bio;
            $user->slug = $user->username;
            $user->password = bcrypt($request->password);
            $user->save();

            $user->roles()->sync($request->roles, true);

            if($request->permissions)
                $user->permissions()->sync($request->permissions, true);

            if($user){
                Session::flash('user-managed', 'Successfully created new user.');
                return response()->json([
                    'status' => 'success',
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'For some reason user could not be created.'
                ], 403);
            }

        }
    }

    public function getAllRoles(Request $request) {
        if($request->ajax()) {
            $roles = Role::with(array('permissions' => function ($query) {
                                $query->select('id', 'display_name');
                            }
                        ))->select('id', 'display_name')->get();
            $permissions = Permission::all();

            if($roles){
                return response()->json([
                    'status' => 'success',
                    'roles' => $roles,
                    'permissions' => $permissions,
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not find any roles.'
                ], 404);
            }
        }
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);

        // Make sure admin is not trying to delete him self, this is not allowed
        if(Auth::user()->id != $user->id) {
            if($request->action == 'transfer') {
                $newOwner = User::find($request->transfer_to);
                $user->posts->each->update([ 'user_id' => $newOwner->id ]);

                // Fetch the user again so that we do not delete any old posts
                $user = User::find($request->id);
            }

            // Delete the user and any posts they have associated
            if($user->delete()){
                Session::flash('user-managed', 'Successfully deleted an user.');
                return response()->json([
                    'status' => 'success',
                    'message' => 'deleted',
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not delete the user. Does user still exists?'
                ], 403);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot delete your self. Asimov\'s third law: An admin must protect his own existence.'
            ], 403);
        }
    }

    public function getCrudPostUsers(Request $request)
    {
        if($request->ajax()) {
            $permission = Permission::where('name', 'crud-post')->first();
            $roles = DB::table('permission_role')->where('permission_id', $permission->id)->pluck('role_id')->toArray();
            $crudPostUserIds = DB::table('role_user')->whereIn('role_id', $roles)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $crudPostUserIds)->select('id', 'firstname', 'lastname', 'username')->get();
            // $users = User::whereRoleIs()

            
            $additionalCrudPostUserIds = DB::table('permission_user')->where('permission_id', $permission->id)->pluck('user_id')->toArray();
            $users = $users->merge(User::whereIn('id', $additionalCrudPostUserIds)->select('id', 'firstname', 'lastname', 'username')->get());

            if($users){
                return response()->json([
                    'status' => 'success',
                    'users' => $users,
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not find any users with crud-post permision.'
                ], 404);
            }
        }
    }


    public function edit(Request $request, $id)
    {
        if($request->ajax()) {
            $user = User::with(array(
                'roles' => function ($query) {
                    $query->select('id', 'display_name')->with(array(
                        'permissions' => function ($query) {
                            $query->select('id', 'display_name');
                        }
                    ));
                }
            ))->with('permissions')->find($id);
            $permissions = Permission::where('name', '!=', 'crud-role', 'AND')->where('name', '!=', 'crud-permission')->get();

            if(Auth::user()->hasRole('super'))
                $roles = Role::with(array('permissions' => function ($query) {
                                $query->select('id', 'display_name');
                            }
                        ))->select('id', 'display_name')->get();
            elseif (Auth::user()->hasRole('admin'))
                $roles = Role::with(array('permissions' => function ($query) {
                                $query->select('id', 'display_name');
                            }
                        ))->select('id', 'display_name')->where('name', '!=', 'super')->get();

            if($user){
                return response()->json([
                    'status' => 'success',
                    'user' => $user,
                    'permissions' => $permissions,
                    'roles' => $roles,
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not find this user. Perhaps it was deleted.'
                ], 401);
            }
        }
    }

    public function update(Request $request)
    {
        if($request->ajax()) {

            $messages = [
                'password.regex' => 'Password needs to have 1 lowercase, 1 upercase and 1 Non-alphanumeric character.',
            ];

            $validator = Validator::make($request->all(), [
                'username' => 'required|alpha_dash|min:3|max:20',
                'email' => 'required|email|unique:users,email,' .$request->id,
                'bio' => 'sometimes|max:300',
                // 'password' => 'sometimes|min:6|confirmed',
                // 'roles' => 'sometimes|array',
                // 'permissions' => 'sometimes|array',
                ],
                $messages
            );

            $validator->sometimes('password', 'bail|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|min:6|confirmed', function ($request) {
                if($request->password != '')
                    return true;
                else {
                    return false;
                }
            });

            $validator->sometimes('firstname', 'alpha|min:3|max:20', function ($request) {
                if($request->firstname != '')
                    return true;
                else {
                    return false;
                }
            });

            $validator->sometimes('lastname', 'alpha|min:3|max:20', function ($request) {
                if($request->lastname != '')
                    return true;
                else {
                    return false;
                }
            });

            $validator->validate();

            $user = User::find($request->id);

            $user->username = $request->username;
            $user->email = $request->email;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->bio = $request->bio;
            $user->slug = null;

            if($request->password)
                $user->password = bcrypt($request->password);

            $user->save();

            if($request->roles)
                $user->roles()->sync($request->roles, true);

            if($request->extrapermissions)
                $user->permissions()->sync($request->extrapermissions, true);
            else
                $user->permissions()->detach();

            $user = User::with(array(
                'roles' => function ($query) {
                    $query->select('id', 'display_name')->with(array(
                        'permissions' => function ($query) {
                            $query->select('id', 'display_name');
                        }
                    ));
                }
            ))->with('permissions')->find($request->id);

            if($user){
                return response()->json([
                    'status' => 'success',
                    'user' => $user,
                ], 201);
            }

        }
    }
}
