<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class LikeController extends Controller
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
        $Likes = Like::all();
        return $this->successResponse($Likes);
    }

    public function show($id)
    {
        $Like = Like::findOrFail($id);
        return $this->successResponse($Like);
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
        $Like = Like::findOrFail($id);
        $Like->delete();
        return $this->successResponse($Like);
    }

    //Devuelve la cantidad de likes de un post dado
    public function totalLikes($post_id)
    {
        $cantLikes = Like::where('idPost', '=', $post_id)->get()->count();
        return $cantLikes;
    }
}
