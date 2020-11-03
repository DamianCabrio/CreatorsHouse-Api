<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;


class VideoController extends Controller
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
        $Videos = Video::all();
        return $this->successResponse($Videos);
    }

    public function show($id)
    {
        $Video = Video::findOrFail($id);
        return $this->successResponse($Video);
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
        $Video = Video::findOrFail($id);
        $Video->delete();
        return $this->successResponse($Video);
    }
}
