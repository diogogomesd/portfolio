<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['except'=> ['create', 'login']]);
    }


    //função para criação de usuario
    public function create(Request $request){
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email'=> 'required|email',
            'password'=> 'required'
        ]);

        //validador dos campos preenchidos
        if(!$validator->fails()){
            $name = $request->input('name');
            $email = $request->input('email');
            $password = $request->input('password');

            //verifica se o email ja consta no banco de dados
            $emailExists = User::where('email', $email)->count();
            //realiza a inserção no banco de dados
            if($emailExists === 0){
                //transform o que foi digitado no campo password em um hash
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = $hash;
                $newUser->save();

                $token = auth()->attempt([
                    'email' => $email,
                    'password'=> $password
                ]);
                if(!token){
                    $array['error'] = 'Ocorreu um erro!';
                    return $array;
                }
                $info = auth()->user();
                $info['avatar'] = url('media/avatars/'.$info['avatar']);
                $array['data'] = $info;
                $array['token'] = $token;

            }else{
                $array['error'] = 'E-mail já cadastrado';
                return $array;
            }

        }else{
            $array['error'] = 'Dados incorretos';
            return $array;
        }

        return $array;
    }

    //função para realizar login
    public function login(Request $request){
        $array = ['error'=> ''];
        
        //pega os dados do usuario
        $email = $request->input('email');
        $password = $request->input('password');

        //verifica se existem
        $token = auth()->attempt([
            'email'=> $email,
            'password'=>$password
        ]);

        //da aviso caso não haja usuario
        if(!$token){
            $array['error'] = 'Usuário e/ou senha estão incorretos';

            return $array;
        }

        //pega os dados do usuário com avatar
        $info = auth()->user();
        $info['avatar'] = url('media/avatars/'.$info['avatar']);
        $array['data'] = $info;
        $array['token'] = $token;

        return $array;
    }
}
