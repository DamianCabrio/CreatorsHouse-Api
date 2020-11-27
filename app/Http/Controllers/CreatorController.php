<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Creator;
use App\Models\Follow;
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

    // Solo guardar el banner
    public function upload(Request $request)
    {

        // $files = $request->file;
        //$request->file->storeAs('uploads', uniqid('img_') . $request->file->getClientOriginalName());
        // image will be stored at storage/app/public/uploads
        // return $request;
        // if (!empty($files)) {
        //     foreach ($files as $file) {
        //         Storage::put($file - getClientOriginalName(), file_get_contents($file));
        //         return ['file' => $file];
        //     }
        // }

        $file = $request->file('banner');
        $path = "uploads/profile/banner";
        $fileName = uniqid() . "_" . $file->getClientOriginalName();
        $file->move($path, $fileName);
        $bannerFullPath = $path . "/" . $fileName;

        return response()->json([
            'request' => $request,
            'message' => 'Se guardo la imagen',
        ]);
    }


    public function store(Request $request)
    {
        $rules = [
            'banner' => 'mimes:jpeg,bmp,png,jpg',
            'description' => 'required|max:255',
            'instagram' => 'URL',
            'youtube' => 'URL',
            'costVip' => 'required|min:0',
            "categories.*" => "required|exists:category",
        ];

        //validación de datos
        $this->validate($request, $rules);

        $fields = $request->all();
        $fields["idUser"] = $request->user()->id;

        //Verifica que exista la category
        $categories = explode(",", $fields["categories"]);
        $category = Category::findOrFail($categories);

        if ($category) {
            //Alta del nuevo creador
            $creator = Creator::create($fields);

            if ($request->has("banner")) {
                $file = $request->file('banner');
                $path = "images/creators/" . $creator->id . "/profile/banner";
                $fileName = uniqid() . "_" . $file->getClientOriginalName();
                $file->move($path, $fileName);
                $bannerFullPath = $path . "/" . $fileName;
                $creator["banner"] = $bannerFullPath;
                $creator->save();
            }

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
