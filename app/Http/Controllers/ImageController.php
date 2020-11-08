<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Like;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class ImageController extends Controller
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
        $Images = Image::all();
        return $this->successResponse($Images);
    }

    public function show($id)
    {
        $Image = Image::findOrFail($id);
        return $this->successResponse($Image);
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
        $Image = Image::findOrFail($id);
        $Image->delete();
        return $this->successResponse($Image);
    }
}
