<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Creator;
use App\Models\User;
use App\Models\Follow;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

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
        //$user = User::where("id", "=", $creator['$idUser'])->get();
        $Creator = Creator::findOrFail($id);
        $Creator['user'] = User::where("id", "=", $Creator['idUser'])->get();;
        $Creator['cantFollowers'] = Follow::where("idCreator", $id)->get()->count();
        return $this->successResponse($Creator);
    }

    public function store(Request $request)
    {
        $rules = [
            'banner' => 'mimes:jpeg,bmp,png',
            'description' => 'required|max:255',
            'instagram' => 'URL',
            'youtube' => 'URL',
            'costVip' => 'min:0',
            "categories.*" => "required|exists:category",
        ];

        //validación de datos
        $this->validate($request, $rules);

        $fields = $request->all();
        $fields["idUser"] = $request->user()->id;
        $creator = Creator::create($fields);
        $categories = explode(",", $fields["categories"]);
        $category = Category::findOrFail($categories);
        $creator->categories()->attach($category);

        return $this->successResponse($creator, 201);
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
}
