<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\User;
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
        $creator = Creator::find($creator_id);
        $postsCreator = $creator->posts;
        return json_encode($postsCreator);
    }
}
