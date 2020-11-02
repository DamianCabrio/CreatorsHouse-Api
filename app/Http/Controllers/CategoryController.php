<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

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
        return json_encode($cat_creators);
    }
}
