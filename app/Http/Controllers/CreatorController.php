<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Creator;
use App\Models\Follow;
use App\Models\Like;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    // Upload Basico
    public function uploadBasico(Request $r)
    {
        $file = $r->file('archivo');
        $folder = "images/";
        $r->file('archivo')->move($folder, $file->getClientOriginalName());
        return response()->json([
            "message" => "Correcto",
        ]);
    }

    // Upload Banner
    public function uploadBanner(Request $r)
    {
        if ($r->file('archivo')) {
            $file = $r->file('archivo');
            // Busco el id del creador
            $idUser = $r->user()->id;
            $creator = Creator::where('idUser', $idUser)->first();
            // Armo la ruta destino
            $folder = "images/creators/" . $creator['id'] . "/profile/banner/";
            $nameFile = uniqid() . "_" . $file->getClientOriginalName();
            // Valid file extensions
            $extension = $file->extension();
            $valid_extensions = array("jpg", "jpeg", "png", "pdf");
            // Check extension
            if (in_array(strtolower($extension), $valid_extensions)) {
            } else {
                return response()->json([
                    "message" => "Extensión invalida",
                ]);
            }
            //Copio el archivo
            if ($r->file('archivo')->move($folder, $nameFile)) {
                $creator["banner"] = $folder . $nameFile;
                $creator->save();
                return response()->json([
                    "message" => "Se guardo el banner",
                ]);
            } else {
                return response()->json([
                    "message" => "Error no se guardo",
                ]);
            }
        } else {
            return response()->json([
                "message" => "No recibio el archivo",
            ]);
        }
    }

    // Solo guardar el banner
    public function upload(Request $request)
    {
        /* $file = $request->file('banner');
        $idCreator = $request->idCreator;
        $path = "images/creators/" . $idCreator . "/profile/banner";
        $fileName = uniqid() . "_" . $file->getClientOriginalName();
        $file->move($path, $fileName);
        //$bannerFullPath = $path . "/" . $fileName;
        $creator = Creator::where("id", $idCreator)->first();
        $creator["banner"] = $path . '/' . $fileName;
        $creator->save();

        return $this->successResponse($creator); */

        if ($request->file('banner')) {
            $file = $request->file('banner');
            $pathOrigen = $file->path();
            $extension = $file->extension();
            $filename = uniqid() . "_" . $file->getClientOriginalName();
            $idCreator = $request->idCreator;
            $creator = Creator::findOrFail($idCreator);
            // Valid file extensions
            $valid_extensions = array("jpg", "jpeg", "png", "pdf");
            // Check extension
            if (in_array(strtolower($extension), $valid_extensions)) {
                $path = "images/creators/" . $idCreator . "/profile/banner/";
                // Upload file
                //$file->move($destinationPath, $filename);
                if ($file->move($path, $filename)) {


                    $creator["banner"] = $path . $filename;
                    $creator->save();

                    return $this->successResponse('Se guardo el banner', 200);
                } else {
                    return $this->errorResponse('Error no se guardo', 401);
                }
            } else {
                return $this->errorResponse('Extension invalida', 401);
            }
        } else {
            return $this->errorResponse('No recibio el archivo', 401);
        }
    }

    // Alta de creator sin banner
    public function store(Request $request)
    {
        $rules = [
            //'banner' => 'mimes:jpeg,bmp,png,jpg',
            'description' => 'required|max:255',
            'instagram' => 'URL',
            'youtube' => 'URL',
            'costVip' => 'required|min:0',
            "categories.*" => "required|exists:category",
        ];

        //validación de datos
        $this->validate($request, $rules);

        $fields = $request->all();
        //toma el user logueado
        $fields["idUser"] = $request->user()->id;

        //Verifica que exista la category
        $categories = explode(",", $fields["categories"]);
        $category = Category::findOrFail($categories);

        if ($category) {
            //Alta del nuevo creador
            $creator = Creator::create($fields);

            /*  if ($request->has("banner")) {
                $file = $request->file('banner');
                $path = "images/creators/" . $creator->id . "/profile/banner";
                $fileName = uniqid() . "_" . $file->getClientOriginalName();
                $file->move($path, $fileName);
                $bannerFullPath = $path . "/" . $fileName;
                $creator["banner"] = $bannerFullPath;
                $creator->save();
            } */
            //$creator["banner"] = '';
            $creator->save();
            //Falta agregar a tabla categorias x creador
            $creator->categories()->attach($category);

            $user = User::where("id", $fields["idUser"])->first();
            $user->isCreator = true;
            $user->save();
        }


        return $this->successResponse($creator, 201);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'banner' => 'image|mimes:jpg,png,jpeg|max:2048',
            'description' => 'max:255',
            'instagram' => 'URL',
            'youtube' => 'URL',
            'costVip' => 'min:0',
            "categories.*" => "exists:category",
        ];


        if ($request->user()->id != $id) {
            //validación de datos
            $this->validate($request, $rules);

            $creator = Creator::findOrFail($id);
            $fields = $request->all();

            if ($request->has("banner")) {
                $file = $request->file('banner');
                $path = "images/creators/" . $creator->id . "/profile/banner";
                $fileName = uniqid() . "_" . $file->getClientOriginalName();
                $file->move($path, $fileName);
                $fields["banner"] = $path . "/" . $fileName;
            }

            $creator->fill($fields);

            if ($creator->isClean()) {
                return $this->errorResponse("At least one value must change", Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $creator->save();
            return $this->successResponse($creator);
        } else {
            return $this->errorResponse("No puede realizar esta accion", 401);
        }
    }

    public function showOne($idUser)
    {
        $Creator = Creator::where("idUser", "=", $idUser)->firstOrFail();
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
        foreach ($check_creators as $creator) {
            //agregar el id del creator
            $creator['idCreator'] = Creator::where("idUser", $creator['id'])->get('id');
        }
        return $this->successResponse($check_creators);
    }

    public function showCreatorsHome()
    {
        $check_creators = User::where("isCreator", 1)->get()->random(3);
        foreach ($check_creators as $creator) {
            //agregar el id del creator
            $creator['idCreator'] = Creator::where("idUser", $creator['id'])->get('id');
        }
        return $this->successResponse($check_creators);
    }

    public function showOneRandCreator()
    {
        $check_creators = User::where("isCreator", 1)->get()->random(1);
        foreach ($check_creators as $creator) {
            //agregar el id del creator
            $creator['idCreator'] = Creator::where("idUser", $creator['id'])->get('id');
        }
        return $this->successResponse($check_creators);
    }

    //Mostrar todos los post del creador (con sus images y videos)
    public function showPostsCreator(Request $request, $creator_id)
    {
        $creator = Creator::findOrFail($creator_id);
        $postsCreator = $creator->posts;
        $isPremium = false;
        $idUser = false;
        if (!is_null($request->user())) {
            $idUser = $request->user()->id;
            $followCreator = Follow::where([["idUser", "=", $idUser], ["idCreator", "=", $creator->id]])->first();
            if (!is_null($followCreator)) {
                $isPremium = $followCreator->isVip ? true : false;
            }
        }

        //recorrer el json, si es tipo 1 text- tipo 2 images- tipo 3 videos
        foreach ($postsCreator as $unPost) {

            if ($idUser != false) {
                $like = Like::where([["idPost", "=", $unPost->id], ["idUser", "=", $idUser]])->first();
                if (!is_null($like)) {
                    $alreadyLiked = true;
                } else {
                    $alreadyLiked = false;
                }
            } else {
                $alreadyLiked = false;
            }

            $unPost["alreadyLiked"] = $alreadyLiked;
            $unPost['cantLikes'] = $unPost->likes->count();

            if (!$unPost->isPublic && !$isPremium) {
                $unPost["content"] = "";
                $unPost['isPrivate'] = true;
            } else {
                $unPost['isPrivate'] = false;
                //carga imagenes del post $unPost.cantidadLikes = $cantidadLikes;
                $unPost['images'] = $unPost->images;
                //carga videos del post
                $unPost['videos'] = $unPost->videos;
                //carga cantidad de likes del post? o likes del post?
                //$unPost['Likes'] = $unPost->l ikes;
            }
        }
        return json_encode($postsCreator);
    }
}
