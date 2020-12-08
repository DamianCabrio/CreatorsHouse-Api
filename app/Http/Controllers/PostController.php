<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Follow;
use App\Models\Image;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
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

    public function index(Request $request, $creator_id)
    {
        $Posts = Post::all();
        return $this->successResponse($Posts);
    }

    public function show(Request $request, $idCreator, $idPost)
    {
        $post = Post::findOrFail($idPost);
        $creator = Creator::findOrFail($post->idCreator);

        $isPremium = false;
        $idUser = false;
        if (!is_null($request->user())) {
            $idUser = $request->user()->id;
            $followCreator = Follow::where([["idUser", "=", $idUser], ["idCreator", "=", $creator->id]])->first();
            if (!is_null($followCreator)) {
                $isPremium = $followCreator->isVip ? true : false;
            }
        }
        if ($idUser != false) {
            $like = Like::where([["idPost", "=", $post->id], ["idUser", "=", $idUser]])->first();
            if (!is_null($like)) {
                $alreadyLiked = true;
            } else {
                $alreadyLiked = false;
            }
        } else {
            $alreadyLiked = false;
        }

        $post["alreadyLiked"] = $alreadyLiked;
        $post['cantLikes'] = $post->likes->count();

        if (!$post->isPublic && !$isPremium) {
            $post["content"] = "";
            $post['isPrivate'] = true;
        } else {
            $post['isPrivate'] = false;
            //carga imagenes del post $unPost.cantidadLikes = $cantidadLikes;
            $post['images'] = $post->images;
            //carga videos del post
            $post['videos'] = $post->videos;
            $post["comments"] = $post->comments;

            foreach ($post["comments"] as $i => $comment){
                $user = User::where("id",$comment->idUser)->firstOrFail();
                $comment["user"] = $user;
            }

            //carga cantidad de likes del post? o likes del post?
            //$unPost['Likes'] = $unPost->l ikes;
        }

        return json_encode($post);
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
            $fields["isPublic"] = $fields["isPublic"] == "0" ? false : true;
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
