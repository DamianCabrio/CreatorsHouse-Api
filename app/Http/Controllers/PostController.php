<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Image;
use App\Models\Post;
use App\Models\Video;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class PostController extends Controller
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
        $Posts = Post::all();
        return $this->successResponse($Posts);
    }

    public function show($id)
    {
        $Post = Post::findOrFail($id);
        return $this->successResponse($Post);
    }

    public function store(Request $request, $creatorId)
    {
        $rules = [
            'content' => 'required|max:1000',
            'tipo' => 'required|min:1|max:3',
            'title' => 'required|max:255',
            'isPublic' => 'required|boolean',
            "video" => "url|required_if:tipo,==,3",
            "imagenes" => "required_if:tipo,==,2",
        ];

        //TODO VALIDAR CREADOR

        $creator = Creator::findOrFail($creatorId);

        //validación de datos
        $this->validate($request, $rules);
        $fields = $request->except(["video", "imagenes"]);
        $fields["idCreator"] = (int)$creatorId;
        $fields["isPublic"] = (bool)$fields["isPublic"];
        $post = Post::create($fields);

        /*         if ($fields["tipo"] == 2 && $request->hasFile("imagenes")) {

                    $file = $request->file('imagenes')->getClientOriginalName();
                    $filaName = uniqid() . "_" . $file;
                    $path = 'photos/';
                    $destPath = public_path($path);
                    $request->file('photo')->move($destPath, $filaName);

                   $allowedfileExtension = ['jpg', 'png', "jpge"];
                    $files = $request->file('imagenes');
                    foreach ($files as $file) {
                        $extension = $files->getClientOriginalExtension();

                        $check = in_array($extension, $allowedfileExtension);

                        if ($check) {
                            return $request->imagenes;
                            foreach ($request->imagenes as $mediaFiles) {

                                $path = $mediaFiles->store("images/" . $creator->username . "/" . $post->id);

                                Image::create([
                                    'idPost' => $post->id,
                                    'image' => $path
                                ]);
                            }
                        } else {
                            return $this->errorResponse("Formato Invalido", 422);
                        }
                    }*/
        //}
        if ($fields["tipo"] == 3) {
            Video::create([
                'idPost' => $post->id,
                'video' => $request->video
            ]);
        }
        return $this->successResponse($post, 201);
    }

    public function update(Request $request, $id)
    {
        //TODO Implementar
    }

    public function delete($id)
    {
        $Post = Post::findOrFail($id);
        $Post->delete();
        return $this->successResponse($Post);
    }
}
