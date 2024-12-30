<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function testing(Request $request){
        return "Probando el controlador de usuarios";
    }

    public function register(Request $request){
        $data= array(
            'status' => 'success',
            'code' => 200,
            'message' => '',
            'data' => array(),
            'errors' => array()
        );
        // Recoger los datos del usuario
        $json = $request->input('json', null);
        // Decodificar los datos
        $params_array = json_decode($json, true);
        $params_array = array_map('trim', $params_array);
        if(!empty($params_array)){
            // Validar los datos del usuario
            $validate = \Validator::make($params_array, [
                'name'      => 'required|alpha',
                'surname'   => 'required|alpha',
                'email'     => 'required|email|unique:users',
                'password'  => 'required'
            ]);

            if($validate->fails()){
                $data['code'] = 400;
                $data['status'] = 'error';
                $data['message'] = "Error en la validación de datos";
                $data['errors'] = $validate->errors()->toArray();

            }
            else{
                // Cifrar la contraseña
                //$pass= password_hash($params_array['password'] , PASSWORD_BCRYPT, array('cost' => 4));
                $pass= bcrypt($params_array['password']);
                $params_array['password'] = $pass;
                // Crear el usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $params_array['password'];
                $user->save();
                unset($params_array['password']);
                $data['code'] = 201;
                $data['status'] = 'success';
                $data['message'] = "Usuario creado con éxito";
                $data['data'] = $params_array;
            }
        }
        else{
            $data['code'] = 400;
            $data['status'] = 'error';
            $data['message'] = "Error en la validación de datos";
            $data['errors'] = array('error' => 'Los datos enviados no son correctos');
        }
        return response()->json($data, $data["code"]);
    }
    public function login(Request $request){
        return "Aqui se logueara el usuario";
    }
}
