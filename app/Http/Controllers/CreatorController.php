<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\User;
use App\Traits\ApiResponser;
use PhpParser\Node\Stmt\Foreach_;

class CreatorController extends Controller
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
        $Creators = Creator::all();
        return $this->successResponse($Creators);
    }

    public function show($id)
    {
        $Creator = Creator::findOrFail($id);
        return $this->successResponse($Creator);
    }

    public function showOne($idUser)
    {
        $Creator = Creator::where("idUser", "=", $idUser)->get();
        return $this->successResponse($Creator);
    }

    public function delete($id)
    {
        $Creator = Creator::findOrFail($id);
        $Creator->delete();
        return $this->successResponse($Creator);
    }

    public function showCreators()
    {
        $check_creators = User::where("isCreator", 1)->get();
        return $this->successResponse($check_creators);
    }

    public function showCreatorsHome()
    {
        $check_creators = User::where("isCreator", 1)->get()->random(3);
        return $this->successResponse($check_creators);
    }
    public function showOneRandCreator()
    {
        $check_creators = User::where("isCreator", 1)->get()->random(1);
        return $this->successResponse($check_creators);
    }
    //Mostrar todos los post del creador (con sus images y videos)
    public function showPostsCreator($creator_id)
    {
        $creator = Creator::findOrFail($creator_id);

        $postsCreator = $creator->posts;
        //recorrer el json, si es tipo 1 text- tipo 2 images- tipo 3 videos
        foreach ($postsCreator as $unPost) {
            //carga imagenes del post $unPost.cantidadLikes = $cantidadLikes;
            $unPost['images'] = $unPost->images;
            //carga videos del post
            $unPost['videos'] = $unPost->videos;
            //carga cantidad de likes del post? o likes del post?
            $unPost['cantLikes'] = $unPost->likes->count();
            //$unPost['Likes'] = $unPost->l ikes;
        }
        return json_encode($postsCreator);
    }

    //Mostrar todos los post del todos los creadores a los q sigue un usuario (con sus images y videos)
    /*  public function showPostsFollows($user_id)
    {
        //$creator = Creator::findOrFail($creator_id);
        $user = User::findOrFail($user_id);
        $creatorsFollow = $user->followers;

        foreach ($creatorsFollow as $unFollow) {
            //cargar los datos del creator
            $creator = $unFollow->creator;
            $creator['User'] = $creator->user();

            $postsCreator = $creator->posts;
            //recorrer el json, si es tipo 1 text- tipo 2 images- tipo 3 videos
            /*  foreach ($postsCreator as $unPost) {
                //carga imagenes del post $unPost.cantidadLikes = $cantidadLikes;
                $unPost['images'] = $unPost->images;
                //carga videos del post
                $unPost['videos'] = $unPost->videos;
                //carga cantidad de likes del post? o likes del post?
                $unPost['cantLikes'] = $unPost->likes->count();
                //$unPost['Likes'] = $unPost->l ikes;
            } */
    //}
    //return json_encode($creatorsFollow);
    //return json_encode($creatorsFollow);
    //} 
}
