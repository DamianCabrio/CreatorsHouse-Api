<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Follow;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;


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
}
