<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ApiResponser;

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
        return $this->successResponse($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->successResponse($user);
    }

    public function store(Request $request)
    {
        $rules = [
            //nickname es requerido
            'username' => 'required|max:10',
            //el email es requerido, tiene formato de email y es unico
            'email' => 'required|email|unique:user',
            //el email es requerido, tiene formato de email y es unico
            'password' => 'required|min:8',
            //avatar
            'avatar' => 'mimes:jpeg,bmp,png'
        ];

        //validación de datos
        $this->validate($request, $rules);

        $fields = $request->all();
        $fields["password"] = Hash::make($request->password);

        $user = User::create($fields);

        $data['token'] = $user->createToken('CreatorHouse')->accessToken;
        $data['name'] = $user->name;

        return $this->successResponse($user, 201, $data);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            //nickname es requerido
            'username' => 'max:10',
            //el email es requerido, tiene formato de email y es unico
            'email' => 'email|unique:user,email,' . $id,
            //el email es requerido, tiene formato de email y es unico
            'password' => 'min:8',
            //avatar
            'avatar' => 'mimes:jpeg,bmp,png'
        ];
        $this->validate($request, $rules);

        $user = User::findOrFail($id);
        $user->fill($request->all());

        if ($request->has("password")) {
            $user->password = Hash::make($request->password);
        }

        if ($user->isClean()) {
            return $this->errorResponse("At least one value must change", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->save();
        return $this->successResponse($user);
    }


    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->successResponse($user);
    }

    public function me(Request $request)
    {
        return $this->successResponse($request->user());
    }
}
