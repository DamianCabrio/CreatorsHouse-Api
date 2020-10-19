<?php

namespace App\Http\Controllers;

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
    }

    public function show(User $user)
    {
        return $this->showOne($user);
    }

    public function store(Request $request)
    {
        //validación de datos
        $this->validate($request,
            [   //nickname es requerido
                'username' => 'required|max:10',
                //el email es requerido, tiene formato de email y es unico
                'email' => 'required|email|unique:user',
                //el email es requerido, tiene formato de email y es unico
                'password' => 'required|min:8',
                //avatar
                'avatar' => 'mimes:jpeg,bmp,png'
            ]);

        $user = User::create($request->all());

        return $this->showOne($user, 201);
    }

    public function update(Request $request, User $user)
    {
        //
    }


    public function delete(User $user)
    {
        $user->delete();
        return $this->showOne($user);
    }
    //
}
