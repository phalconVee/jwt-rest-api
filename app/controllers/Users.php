<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Users extends CI_Controller {

    protected $tokenKey;
    protected $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('cors');
        $this->cors->allowCORS();
        $this->config->load('jwt', TRUE);
        $this->apiKey = '';
        $this->tokenKey = $this->config->item('jwt_token_key', 'jwt');
    }

    public function register()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST') {
            json_output(400,[
                'message' => 'Bad HTTP Request. Expecting a POST request instead.'
            ]);
        } else {
            $request = json_decode(file_get_contents('php://input'), true);
            $password = $request['password'];
            $options  = array("cost" => 10);
            $hashPassword = password_hash($password, PASSWORD_BCRYPT, $options);

            $data = array(
              'name' => $request['name'],
              'email' => $request['email'],
              'password' => $hashPassword,
            );

            $this->db->insert('users', $data);

            json_output(200,[
                'registered' => true
            ]);
        }
    }

	public function login()
	{
        $method = $_SERVER['REQUEST_METHOD'];
        if($method != 'POST') {
            json_output(400,[
                'message' => 'Bad HTTP Request. Expecting a POST request instead.'
            ]);
        } else {
            $request = json_decode(file_get_contents('php://input'), true);
            $email = $request['email'];
            $password = $request['password'];

            $user = $this->db->get_where('users', array('email'=>$email))->row_array();

            if(count($user) > 0 && password_verify($password, $user['password'])) {
                // generate new api token
                $key = $this->tokenKey;
                $token = array(
                    "iss" => "http://example.org",
                    "aud" => "http://example.com",
                    "iat" => 1356999524,
                    "nbf" => 1357000000
                );

                $api_token = JWT::encode($token, $key);

                $data = [
                    'api_token' => $api_token
                ];

                $this->db->update('users', $data, array('id'=>$user['id']));

                json_output(200,[
                    'authenticated' => true,
                    'api_token' => $api_token,
                    'user_id' => $user['id']
                ]);
            }
            json_output(422,[
                'message' => 'Provided email and password does not match!'
            ]);
        }
	}

	public function logout()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        $user_id = $request['user_id'];
        $data = [
            'api_token' => null
        ];

        $this->db->update('users', $data, array('id'=>$user_id));

        json_output(200,[
            'done' => true
        ]);
    }
}
