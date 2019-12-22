<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * Get users
     *
     * @return [array] user objects
     */
    public function index(Request $request)
    {
        if($request->ajax()) {

            $users = $this->user->all();

            return datatables()->of($users)
                 ->editColumn('type', function ($user) {
                    if(!is_null($user->type))
                        return __('options.user_type')[$user->type];
                    else
                        return '-';
                })
                ->editColumn('action', function ($user) {
                    $button = "";

                    $button .= '<a onclick="edit('.$user->id.')" href="javascript:;" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a> ';

                    $button .= '<a onclick="remove('.$user->id.')" href="javascript:;" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>';

                    return $button;
                })
                ->make(true);
        }

        return view('users.index');
    }

    /**
     * Get users
     *
     * @return [array] user objects
     */
    public function show(Request $request, User $user)
    {
    	return response()->json([
    		'user' => $user,
    		'status' => [
            	'code' => 200,
            	'message' => 'Success'
            ]
    	]);
    }

    /**
     * Create user
     *
     * @param  [integer] user id
     * @return [json] user onject
     */
    public function edit(User $user) 
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [json] status
     */
    public function store(Request $request)
    {
    	// Validation
    	$validator = \Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'type' => 'required|alpha|max:1|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'title' => 'Failed!', 
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ]);
        }

        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'type' => $request->type
        ]);

        return response()->json(['status' => 'success', 'title' => 'Success!', 'message' => 'Data has been saved.']);
    }

    /**
     * Get users
     *
     * @return [array] user objects
     */
    public function update(Request $request, User $user)
    {
    	// Validation
    	$validator = \Validator::make($request->all(), [
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|string|email|unique:users,email,'.$user->id,
            'type' => 'sometimes|required|alpha|max:1|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
            	'errors' => $validator->errors(),
            	'status' => [
	            	'code' => 422,
	            	'message' => 'Validation failed'
	            ]
            ]);
        }

    	$update = $user->update($request->all());

    	return response()->json(['status' => 'success', 'title' => 'Success!', 'message' => 'Data has been updated.']);
    }

    /**
     * Delete users
     *
     * @return [string] status
     */
    public function delete(User $user)
    {
        $user->delete();
        
        return response()->json(['status' => 'success', 'title' => 'Success!', 'message' => 'Data has been deleted.']);
    }
}
