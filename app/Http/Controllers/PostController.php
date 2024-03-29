<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Exception;

class PostController extends Controller
{
    //Retorna todos os resultados de acordo com o id do usuario logado
    public function index(){
        try{
            $user = auth()->user();
            $user_id = $user->id;
       
            $retorno = Post::where('user_id', '=', $user_id)
            ->get()
            ->toJson(JSON_PRETTY_PRINT);
            
            return response($retorno, 200);
        }catch(Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
       
    }

    //Função para fazer uma busca por um dado especifico, caso não queira buscar nada ou não tenha o dado, ele irá trazer todos os dados 
    public function Search($search){
        try{
            $user = auth()->user();
            $user_id = $user->id;
            if($search){
                $retorno = Post::where([
                    ['user_id', '=', $user_id],
                    ['title', 'like', '%'.$search.'%']
                ])->get();
    
            }else{
                $retorno = Post::where('user_id', '=', $user_id)
                ->get()
                ->toJson(JSON_PRETTY_PRINT);
            }
            if($retorno == "[]"){
                return response()->json(['status' => 'error'], 404);
                
            }else{
                return response($retorno, 200);
            }
            
        }catch(Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
        
    }

    //Cria um novo dado
    public function store(Request $request){
        try{
            $post = new Post();
            $post->title = $request->title;
            $post->body = $request->body;

            $user = auth()->user();
            $post->user_id = $user->id;
          
            if($post->save()){    
                return response()->json(['status' => 'sucess','message' => 'Post created sucessfully']);
            }
            
        }catch(Exception $e){
            return response()->json(['status' => 'error','message' => $e->getMessage()]);
        }
    }

    //Edita o conteudo escolhido
    public function update(Request $request, $id){
        try{
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            $post->body = $request->body;

            if($post->save()){
                return response()->json(['status' => 'sucess','message' => 'Post update sucessfully']);
            }
        }catch(Exception $e){
            return response()->json(['status' => 'error','message' => $e->getMessage()]);
        }
    }

    //Exclui um dado
    public function destroy($id){
        try{
            $post = Post::findOrFail($id);
            
            if($post->delete()){
                return response()->json(['status' => 'sucess','message' => 'Post deleted sucessfully']);
            }
        }catch(Exception $e){
            return response()->json(['status' => 'error','message' => $e->getMessage()]);
        }
    }
}


