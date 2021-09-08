<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tayang extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->auth();  // fungsi dari bd controler /app/core/BD_Controller
        $this->load->model('M_tayang');
    }
	
    public function index_post()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->post();
      }         
        $max =  $this->M_tayang->get_max();
        $urutan = $max->urut + 1;
      
   
        $id_baru = id_baru();
        $data = array(
          'iklan_tayang_id' => $id_baru,
          'iklan_tayang_jam' => $i['jam_tayang'],
          'id_iklan' => $i['id_iklan'],
          'iklan_tayang_hari' => $i['hari_tayang'],
          'iklan_tayang_urut' => $urutan
      );
        $this->M_tayang->tambah($data);
        $cek = $this->M_tayang->get_by_id($id_baru);
      if($cek){
        $respone = array(
          "msg" => "data  berhasil di tambahkan",
          "success" => true );
      }else{
        $respone = array(
          "msg" => "data  gagal di tambahkan",
          "success" => false );
      }
      $this->set_response($respone, REST_Controller::HTTP_OK);
    }
    
    public function index_get()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->get();
      }
      $where = array();
      if (!empty($i['id_tayang_iklan'])) {
        $where['iklan_tayang_id'] =$i['id_iklan_tayang'];
      }
      if (!empty($i['jam_tayang'])) {
        $where['iklan_tayang_jam'] =$i['jam_tayang'];
      }
      if (!empty($i['hari_tayang'])) {
        $where['iklan_tayang_hari'] = $i['hari_tayang'];
      }
      $data = $this->M_tayang->get_all($where);
      $this->set_response($data, REST_Controller::HTTP_OK);

    }
    public function index_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->put();
      }
      $where['iklan_tayang_id'] =$i['id_iklan_tayang'];
      $data = array();
      if (!empty($i['jam_tayang'])) {
        $data['iklan_tayang_jam'] =$i['jam_tayang'];
      }
      if (!empty($i['hari_tayang'])) {
        $data['iklan_tayang_hari'] = $i['hari_tayang'];
      }
      if (!empty($i['tayang_urut'])) {
        $data['iklan_tayang_urut'] = $i['tayang_urut'];
      }
      $this->M_tayang->update($data,$where);
      $cek = $this->M_tayang->get_by_id($i['id_iklan_tayang']);
      if($cek){
        $respone = array( 
          'msg'     => 'data berhasil di update',
          'success' => true
        );
      }else{
        $respone = array( 
          'msg'=> 'data tidak ditemkan',
          'success' => false
          );
      }
      $this->set_response($respone, REST_Controller::HTTP_OK);

    }
  
    public function index_delete()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->delete();
      }
      $data = array(
        'iklan_tayang_id' => $i['id_iklan_tayang'],
      );
     
      $this->M_tayang->hapus($data);
      $cek = $this->M_tayang->get_by_id($data['iklan_tayang_id']);
      if(!$cek){
        $respone = array( 
          'msg'     => 'data berhasil di hapus',
          'success' => true
        );
      }else{
        $respone = array( 
          'msg'=> 'data tidak ditemkan',
          'success' => false
          );
      }
      $this->set_response($respone, REST_Controller::HTTP_OK);

    }

    


}
