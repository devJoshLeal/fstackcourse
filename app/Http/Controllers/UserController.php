<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public $dataTemplate = array(
        'status'    => 'success',
        'code'      => 200,
        'message'   => '',
        'data'      => array(),
        'errors'    => array()
    );
    public function testing(Request $request){
        return "Probando el controlador de usuarios";
    }

    public function register(Request $request){
        $data= $this->dataTemplate;
        // Recoger los datos del usuario
        $json = $request->input('json', null);
        // Decodificar los datos
        $params_array = json_decode($json, true);
        $params_array = array_map('trim', $params_array);
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
            $pass= hash( 'sha256', $params_array['password'] );
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

        return response()->json($data, $data["code"]);
    }
    public function login(Request $request){
        $data = $this->dataTemplate;
        $jwtAuth = new \JwtAuth();
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $params_array = array_map('trim', $params_array);
        // Validar los datos del usuario
        $validate = \Validator::make($params_array, [
            'email'     => 'required|email',
            'password'  => 'required',
            'getToken'  => 'boolean'
        ]);
        if($validate->fails()){
            $data['code'] = 400;
            $data['status'] = 'error';
            $data['message'] = "Error en la validación de datos";
            $data['errors'] = $validate->errors()->toArray();
            return response()->json($data, $data['code']);
        }
        else{

            // Cifrar la contraseña
            $pass = hash( 'sha256', $params_array['password'] );
            $params_array['password'] = $pass;
            return $jwtAuth->signup(
                $params_array['email'],
                $params_array['password'],
                isset($params_array['getToken']) ? $params_array['getToken'] : false,
            );
        }


    }
    public function update(Request $request){
        $data = $this->dataTemplate;
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        // Actualizar datos del usuario
        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $userByToken = $jwtAuth->checkToken($token, true);

        // Validar Datos
        $validate = \Validator::make($params_array, [
            'name'      => 'required|alpha',
            'surname'   => 'required|alpha',
            'email'     => 'required|email|unique:users,email,' . $userByToken->sub,
        ]);

        if($validate->fails()){
            $data['code'] = 400;
            $data['status'] = 'error';
            $data['message'] = "Error en la validación de datos";
            $data['errors'] = $validate->errors()->toArray();
        } else {
            // Quitar datos que no se van a actualizar
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['updated_at']);
            unset($params_array['remember_token']);

            // Actualizar datos
            $userUpdated = User::where('id', $userByToken->sub)->update($params_array);
            $data['message'] = "Datos actualizados correctamente";
            $data['data'] = $params_array;
        }

        return response()->json($data, $data["code"]);
    }

    public function upload( Request $request){

    }

}
