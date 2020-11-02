<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


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

    public function index()
    {
        $Posts = Post::all();
        return $this->successResponse($Posts);
    }

    public function show($id)
    {
        $Post = Post::findOrFail($id);
        return $this->successResponse($Post);
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
        $Post = Post::findOrFail($id);
        $Post->delete();
        return $this->successResponse($Post);
    }
}
