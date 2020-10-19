<?php

namespace App\Http\Controllers;

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
        return response()->json(User::all());
    }

    public function show(User $user)
    {
        return response()->json(User::find($user));
    }

    public function store(Request $request)
    {
        //validación de datos
        $this->validate($request, 
        [   //nickname es requerido
            'username'=>'required|max:10',
            //el email es requerido, tiene formato de email y es unico
            'email'=>'required|email|unique:user',
            //el email es requerido, tiene formato de email y es unico
            'password'=>'required|min:8',
            //avatar
            'avatar' => 'mimes:jpeg,bmp,png'
        ]);
        
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
