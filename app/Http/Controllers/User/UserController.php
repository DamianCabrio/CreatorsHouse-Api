<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $users = User::all();
        return $this->showAll($users);

        //return response()->json(User::all());
    }

    public function show(User $user)
    {
        return response()->json(User::find($user));
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function delete(User $user)
    {
        User::findOrFail($user)->delete();
        return response('Deleted Successfully', 200);
    }
    //
}
