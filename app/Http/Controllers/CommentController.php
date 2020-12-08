<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ApiResponser;

    public function store(Request $request, $idPost, $idUser)
    {
        if ($request->user()->id != $idUser) {
            return $this->errorResponse("No puede realizar esta accion", 400);
        }
        //TODO: Fijarse si el usuario tiene los permisos suficientes para hacer el comentario

        $rules = [
            'text' => 'required|max:1000',
        ];

        $this->validate($request, $rules);

        $fields = $request->all();
        $fields["idPost"] = (int)$idPost;
        $fields["idUser"] = (int)$idUser;

        $comment = Comment::create($fields);

        $user = User::where("id", $comment->idUser)->firstOrFail();
        $comment["user"] = $user;

        return $this->successResponse($comment, 201);
    }
}
