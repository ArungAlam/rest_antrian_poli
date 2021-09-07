<?php

defined('BASEPATH') OR exit('No direct script access allowed');
//use \Firebase\JWT\JWT;
class Auth extends BD_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('M_user');
    }

    

    public function login_post()
    {
       #Check if Content Type is JSON
        if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
           $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
        }else{
            $i = $this->input->post();
        }
        // print_r($i);
        $u = $i['username']; //Username Posted
        $p = md5($i['password']); //Pasword Posted
        $q = array('usr_loginname' => $u); //For where query condition
        $kunci = $this->config->item('thekey');
        // print_r($this->config->item('thekey')); die();
        $invalidLogin = ['status' => 'Invalid Login']; //Respon if login invalid
        $val = $this->M_user->get_user_full($q); 
        if($this->M_user->get_user($q)->num_rows() == 0){
           $this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);}
	    	$match = $val->usr_password;   //Get password for user from database
        if($p == $match){  //Condition if password matched
        	  $token['id'] = $val->usr_id;  //From here
            $token['username'] = $u;
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            $token['exp'] = $date->getTimestamp() + 60*60*5; //To here is to generate token
            $output['token'] = JWT::encode($token,$kunci); //This is the output token
            $output['username'] = $val->usr_name;
            $output['id'] = $val->usr_id;
            $output['role'] = $val->rol_name;
            $output['id_dep'] = $val->id_dep;
            $this->set_response($output, REST_Controller::HTTP_OK); //This is the respon if success
        }else{
            $this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
        }
    }

}
