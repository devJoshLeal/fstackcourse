<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Post;

class PostController extends Controller
{
    public $apiTemplate = array(
        'status'    => 'success',
        'code'      => 200,
        'message'   => ''
    );
    public $whoAmI = null;
    public function __construct(Request $request){
        $this->middleware('api.auth', ['except' => ['index', 'show']]);
        $this->middleware('api.checkparams', ['except' => ['index', 'show']]);
        $token = $request->header('Authorization',null);
        if(isset($token)){
            $jwtAuth = new \JwtAuth();
            // Consigue los datos del usuario segun el token
            $this->whoAmI = $jwtAuth->checkToken($token, true);
        }
    }

    public function index(){
        $posts = Post::all()->load('category');
        $this->apiTemplate["data"] = $posts;
        return response()->json($this->apiTemplate, $this->apiTemplate['code']);
    }
    public function show($id){
        $post = Post::find($id)->load('category');
        if($post){
            $this->apiTemplate["data"] = $post;
        }
        else{
            $this->apiTemplate["status"] = 'error';
            $this->apiTemplate["code"] = 404;
            $this->apiTemplate["message"] = "Publicacion no encontrada";
        }
        return response()->json($this->apiTemplate, $this->apiTemplate['code']);
    }
    public function store(Request $request){
        $json = $request->input('json', null);
        $paramsArray = json_decode($json, true);
        $validate = \Validator::make($paramsArray, [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);
        if($validate->fails()){
            $this->apiTemplate["status"] = 'error';
            $this->apiTemplate["code"] = 400;
            $this->apiTemplate["message"] = "Error al validar los datos";
            $this->apiTemplate['data'] = $validate->errors();
        }
        else{
            $post = new Post();
            $post->title = $paramsArray["title"];
            $post->content = $paramsArray["content"];
            $post->category_id = $paramsArray["category_id"];
            $post->user_id = $this->whoAmI->sub;
            $post->save();
            $this->apiTemplate["data"] = $post;
            $this->apiTemplate["code"] = 201;
            $this->apiTemplate["message"] = "Publicacion creada con exito";
        }
        return response()->json($this->apiTemplate, $this->apiTemplate['code']);
    }
}
