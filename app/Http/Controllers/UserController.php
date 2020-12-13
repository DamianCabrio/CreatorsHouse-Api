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
        return $this->showAll($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return $this->successResponse($user);
    }

    public function showCreators($id)
    {
        $user = User::findOrFail($id,);
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
        $fields["verification_token"] = User::generateVerificationCode();

        $user = User::create($fields);

        $data['token'] = $user->createToken('users')->accessToken;

        return $this->successResponse($user, 201, $data);
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            //el email es requerido, tiene formato de email y es unico
            'password' => 'required',
        ];

        $this->validate($request, $rules);

        $check_users = User::where("email", $request->email)->first();
        if (@count($check_users) > 0) {
            if ($check_users->isVerified != false) {
                $password = $request->password;
                if (Hash::check($password, $check_users["password"])) {
                    $response["token"] = $check_users->createToken("users")->accessToken;
                    return $this->successResponse($check_users, 200, $response);
                }
            } else {
                return $this->errorResponse("Tiene que verificar su cuenta", 401);
            }
        } else {
            return $this->errorResponse("El login es incorrecto", 401);
        }
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
        $file = $request->file('avatar');
        $path = "images/users/" . $user->id . "/avatar";
        $fileName = uniqid() . "_" . $file->getClientOriginalName();
        $file->move($path, $fileName);

        $fields = $request->all();
        $fields["avatar"] = $path . "/" . $fileName;

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
        return $this->successResponse($request->user(),200);
    }

    public function verify($token)
    {
        $user = User::where("verification_token", $token)->first();

        if ($user == null) {
            return $this->errorResponse("No se pudo verificar el email", 404);
        }

        $user->verification_token = null;
        $user->isVerified = true;
        $user->save();

        return $this->successResponse("Se verifico el email correctamente", 400);
    }

    // Upload Avatar
    public function uploadAvatar(Request $r)
    {
        if ($r->file('archivo')) {
            $file = $r->file('archivo');
            // Busco el id del creador
            $idUser = $r->user()->id;
            $user = User::findOrFail($idUser);
            //$creator = Creator::where('idUser', $idUser)->first();
            // Armo la ruta destino
            $folder = "images/users/" . $idUser . "/avatar/";
            $nameFile = uniqid() . "_" . $file->getClientOriginalName();
            // Valid file extensions
            $extension = $file->extension();
            $valid_extensions = array("jpg", "jpeg", "png", "pdf");
            // Check extension
            if (in_array(strtolower($extension), $valid_extensions)) {
            } else {
                return response()->json([
                    "message" => "Extensión invalida",
                ]);
            }
            //Copio el archivo
            if ($r->file('archivo')->move($folder, $nameFile)) {
                $user["avatar"] = $folder . $nameFile;
                $user->save();
                return response()->json([
                    "message" => "Se guardo el avatar",
                ]);
            } else {
                return response()->json([
                    "message" => "Error no se guardo",
                ]);
            }
        } else {
            return response()->json([
                "message" => "No recibio el archivo",
            ]);
        }
    }
}
