<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Traits\ApiResponser;

class CategoryController extends Controller
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
        $Categories = Category::all();
        return $this->successResponse($Categories);
    }

    public function show($id)
    {
        $Category = Category::findOrFail($id);
        return $this->successResponse($Category);
    }

    public function delete($id)
    {
        $Category = Category::findOrFail($id);
        $Category->delete();
        return $this->successResponse($Category);
    }

    public function showCatCreators($category_id)
    {
        $category = Category::find($category_id);
        $cat_creators = $category->creators;
        foreach ($cat_creators as $creator) {
            //agregar datos del user
            $creator['User'] = User::where("id", $creator['idUser'])->get();
        }
        $cat_creators['nameCategory'] = Category::where("id", $category_id)->get();
        return json_encode($cat_creators);
    }
}
