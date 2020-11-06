<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\User;
use App\Models\Post;
use App\Traits\ApiResponser;

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
        $check_creators = User::where("isCreator", 1)->get()->random(6);
        return $this->successResponse($check_creators);
    }

    //Mostrar todos los post del creador (con sus images y videos)
    public function showPostsCreator($creator_id)
    {
        //$creator = Creator::find($creator_id);
        /* $postsCreator = $creator->posts;
        foreach ($unPost as $postsCreator){
            $unPost::with(['post'])
        } */

        /* //cantidad de likes de cada post del creator
        foreach ($unPost as $postsCreator) {
            $cantidadLikes = count($unPost->likes);
            $unPost.cantidadLikes = $cantidadLikes;
        } */
        //$creatorConPosts = $creator1::with(['posts', 'posts.images', 
        //'posts.videos', 'posts.likes'])->get(); */

        //$users = App\User::with(['posts' => function ($query) {
        //     $query->where('title', 'like', '%first%'); }])->get();
        //$post = Post::where('idCreator', 1);
        /*  $postCompleto = $post::with(['images' => function ($query) {
            $query->where('idCreator', 1) ;}, 'post.videos'])->get(); */
        $postCompleto = Post::with(['posts.images', 'posts.videos']);

        return json_encode($postCompleto);
    }
}
