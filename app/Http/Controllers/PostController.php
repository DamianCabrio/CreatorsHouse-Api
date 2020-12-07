<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Image;
use App\Models\Like;
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
            "imagenes.*" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048"
        ];

        $creatorLogeado = Creator::where('idUser', '=', $request->user()->id)->firstOrFail();

        if ($creatorId == $creatorLogeado->id) {
            //validación de datos
            $this->validate($request, $rules);
            $fields = $request->except(["video", "imagenes"]);
            $fields["idCreator"] = (int)$creatorId;
            $fields["isPublic"] = (bool)$fields["isPublic"];
            $post = Post::create($fields);

            if ($fields["tipo"] == 2 && $request->hasfile("imagenes")) {
                $path = "images/creators/" . $creatorLogeado->id . "/posts/" . $post->id;

                foreach ($request->file('imagenes') as $file) {
                    $fileName = uniqid() . "_" . $file->getClientOriginalName();
                    $file->move($path, $fileName);

                    Image::create([
                        "image" => $path . "/" . $fileName,
                        "idPost" => $post->id
                    ]);
                }
            }
            if ($fields["tipo"] == 3) {
                Video::create([
                    'idPost' => $post->id,
                    'video' => $request->video
                ]);
            }
            return $this->successResponse($post, 201);
        } else {
            return $this->errorResponse("No podes hacer eso, capo", 404);
        }
    }

    public function likePost(Request $request, $idPost, $idUser)
    {

        if (!is_null($idPost) && !is_null($idUser)) {
            if ($idUser == $request->user()->id) {
                $fields["idPost"] = $idPost;
                $fields["idUser"] = $idUser;

                $alreadyLiked = Like::where([["idUser", "=", $idUser], ["idPost", "=", $idPost]])->withTrashed()->first();
                if (is_null($alreadyLiked)) {
                    $like = Like::create($fields);
                    $like->save();
                    return $this->successResponse("Dio like con exito", 200);
                } elseif ($alreadyLiked->deleted_at != null) {
                    $alreadyLiked->restore();
                    return $this->successResponse("Dio like con exito", 200);
                } else {
                    return $this->errorResponse("El like ya fue creado", 403);
                }
            } else {
                return $this->errorResponse("Usted no puede realizar esta acción", 403);
            }
        } else {
            return $this->errorResponse("El usuario o creador no existe", 404);
        }
    }

    public function removeLikePost(Request $request, $idPost, $idUser)
    {

        if (!is_null($idPost) && !is_null($idUser)) {
            if ($idUser == $request->user()->id) {
                $alreadyLiked = Like::where([["idUser", "=", $idUser], ["idPost", "=", $idPost]])->first();
                if (!is_null($alreadyLiked) && $alreadyLiked->deleted_at == null) {
                    $alreadyLiked->delete();
                    return $this->successResponse("Retiro su like", 200);
                } else {
                    return $this->errorResponse("Usted no le dio like a este post", 200);
                }
            } else {
                return $this->errorResponse("Usted no puede realizar esta acción", 403);
            }
        } else {
            return $this->errorResponse("El usuario o post no existe", 404);
        }
        return $this->successResponse("Error");
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
