<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\Follow;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


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

    public function store(Request $request)
    {
        //TODO Implementar
    }

    public function update(Request $request, $id)
    {
        //TODO Implementar
    }

    public function delete($id)
    {
        $Follow = Follow::findOrFail($id);
        $Follow->delete();
        return $this->successResponse($Follow);
    }

    //Todos los creadores a los que sigue un usuario
    public function following($idUser)
    {
        $user = User::findOrFail($idUser);
        $following = $user->followers;
        foreach ($following as $follow) {
            //Datos del user
            $following['user'] = $follow->user;
            //Datos del creator
            //$following['creator'] = $follow->creator;

            $creator = $follow->creator;

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
