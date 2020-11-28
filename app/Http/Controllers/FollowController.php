<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Creator;
use Illuminate\Support\Facades\Http;


class FollowController extends Controller
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
        $Follows = Follow::all();
        return $this->successResponse($Follows);
    }

    public function show($id)
    {
        $Follow = Follow::findOrFail($id);
        return $this->successResponse($Follow);
    }

    public function store(Request $request, $idUser, $idCreator)
    {

        $user = User::findOrFail($idUser);
        $creator = Creator::findOrFail($idCreator);

        if (!is_null($user) && !is_null($creator)) {
            if ($idUser == $request->user()->id) {
                $fields["idCreator"] = $request->idCreator;
                $fields["idUser"] = $request->user()->id;
                $fields["isVip"] = false;

                $alreadyFollow = Follow::where([["idCreator", "=", $idCreator], ["idUser", "=", $idUser]])->withTrashed()->first();
                if (is_null($alreadyFollow)) {
                    $follow = Follow::create($fields);
                    $follow->save();
                    return $this->successResponse("Siguio al creador con exito", 400);
                } elseif ($alreadyFollow->deleted_at != null) {
                    $alreadyFollow->restore();
                    return $this->successResponse("Siguio al creador con exito", 400);
                } else {
                    return $this->errorResponse("Usted ya sige a este creador", 403);
                }
            } else {
                return $this->errorResponse("Usted no puede realizar esta acción", 403);
            }
        } else {
            return $this->errorResponse("El usuario o creador no existe", 404);
        }
    }

    public function update(Request $request, $id)
    {
        //TODO Implementar
    }

    public function delete(Request $request, $idUser, $idCreator)
    {
        $user = User::findOrFail($idUser);
        $creator = Creator::findOrFail($idCreator);

        if (!is_null($user) && !is_null($creator)) {
            if ($idUser == $request->user()->id) {
                $alreadyFollow = Follow::where([["idCreator", "=", $idCreator], ["idUser", "=", $idUser]])->first();
                if (!is_null($alreadyFollow) && $alreadyFollow->deleted_at == null) {
                    $alreadyFollow->delete();
                    return $this->successResponse("Ya no sigue mas a este creador", 400);
                } else {
                    return $this->errorResponse("Usted no sigue a este creador", 403);
                }
            } else {
                return $this->errorResponse("Usted no puede realizar esta acción", 403);
            }
        } else {
            return $this->errorResponse("El usuario o creador no existe", 404);
        }
        return $this->successResponse($Follow);
    }

    //Todos los creadores a los que sigue un usuario
    public function following($idUser)
    {
        $user = User::findOrFail($idUser);
        $following = $user->followers;
        foreach ($following as $follow) {

            //Datos del creator
            //$following['creator'] = $follow->creator;

            $creator = $follow->creator;
            //Datos del user
            $follow['user'] = User::where('id', $creator['idUser'])->get();

            //Datos de los posts con images, videos, cant likes
            $postsCreator = $creator->posts;
            //recorrer el json, si es tipo 1 text- tipo 2 images- tipo 3 videos
            foreach ($postsCreator as $unPost) {
                //carga imagenes del post $unPost.cantidadLikes = $cantidadLikes;
                $unPost['images'] = $unPost->images;
                //carga videos del post
                $unPost['videos'] = $unPost->videos;
                //carga cantidad de likes del post? o likes del post?
                $unPost['cantLikes'] = $unPost->likes->count();
            }
        }
        return json_encode($following);
    }

    //Todos los post publicos de los creadores a los que sigue un usuario
    public function postsPublic($idUser)
    {
        $user = User::findOrFail($idUser);
        $following = $user->followers;

        foreach ($following as $follow) {

            $posts = Post::where('isPublic', 1);
            $posts = $posts->where('idCreator', $follow['idCreator'])->get();

            $follow['posts'] = $posts;

            foreach ($posts as $unPost) {

                $creator = Creator::findOrFail($unPost['idCreator']);

                $unPost['user'] = User::where('id', $creator['idUser'])->get();
                //carga imagenes del post $unPost.cantidadLikes = $cantidadLikes;
                $unPost['images'] = $unPost->images;
                //carga videos del post
                $unPost['videos'] = $unPost->videos;
                //carga cantidad de likes del post? o likes del post?
                $unPost['cantLikes'] = $unPost->likes->count();
            }
        }
        return json_encode($following);
    }

    //Todos los post premium de los creadores a los que sigue un usuario y esVip
    public function postsPremium($idUser)
    {
        $user = User::findOrFail($idUser);
        $following = $user->followers->where('isVip', 1);

        foreach ($following as $follow) {

            $posts = Post::where('isPublic', 0);
            $posts = $posts->where('idCreator', $follow['idCreator'])->get();

            $follow['posts'] = $posts;

            foreach ($posts as $unPost) {

                $creator = Creator::findOrFail($unPost['idCreator']);

                $unPost['user'] = User::where('id', $creator['idUser'])->get();
                //carga imagenes del post $unPost.cantidadLikes = $cantidadLikes;
                $unPost['images'] = $unPost->images;
                //carga videos del post
                $unPost['videos'] = $unPost->videos;
                //carga cantidad de likes del post? o likes del post?
                $unPost['cantLikes'] = $unPost->likes->count();
            }
        }
        return json_encode($following);
    }

    /*     public function postFollow($user)
    {
        $creatorFollows = Follow::where("idUser", $user)->get();
        $creators = [];
        foreach ($creatorFollows as $follow){
            $creator = Creator::where("id",$follow->idCreator)->get();
            array_push($creators,$creator);
        }

        $posts = [];

        foreach ($creators as $creator) {
            $postsCreator = Post::where("idCreator", $creator[0]->id)->get();

            foreach ($postsCreator as $unPost) {
                //carga imagenes del post $unPost.cantidadLikes = $cantidadLikes;
                $unPost['images'] = $unPost->images;
                //carga videos del post
                $unPost['videos'] = $unPost->videos;
                //carga cantidad de likes del post? o likes del post?
                $unPost['cantLikes'] = $unPost->likes->count();
                $unPost["user"] = $creator[0]->user;
            }
            array_push($posts,$postsCreator);
        }
        return $this->successResponse($posts);
    } */
}
