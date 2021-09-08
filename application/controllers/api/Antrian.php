<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Antrian extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->auth();  // fungsi dari bd controler /app/core/BD_Controller
        $this->load->model('M_antrian');
    }
	
    public function index_post()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->post();
      }         
       
   
        $id_baru = id_baru();
        $data = array(
          'antrian_id' => $id_baru,
          'id_reg' => $i['id_reg'],
          'id_poli' => $i['id_poli'],
          'id_dokter' => $i['id_dokter'],
          'status_antrian' => $i['status_antrian'],
          'nomer_antrian_pasien' => $i['nomer_antrian_pasien'],
          'when_create' => date('Y-m-d H:i:s'),
      );
        $this->M_antrian->tambah($data);
        $cek = $this->M_antrian->get_by_id($id_baru);
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
         $i = $this->input->get();
      }
      $where = array();
      if (!empty($i['status_antrian'])) {
        $where['status_antrian'] =$i['status_antrian'];
      }
      if (!empty($i['id_antrian'])) {
        $where['antrian_id'] =$i['id_antrian'];
      }
      if (!empty($i['id_dokter'])) {
        $where['id_dokter'] =$i['id_dokter'];
      }
      if (!empty($i['id_poli'])) {
        $where['id_poli'] = $i['id_poli'];
      }
      if (!empty($i['when_create'])) {
        $where['DATE(when_create)'] = $i['when_create'];
      }
      $data = $this->M_antrian->get_all($where);
      $this->set_response($data, REST_Controller::HTTP_OK);

    }
    public function index_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->put();
      }
      $where = array();
      if (!empty($i['id_antrian'])) {
        $where['antrian_id'] = $i['id_antrian'];
      };
      if (!empty($i['id_reg'])) {
        $where['id_reg'] = $i['id_reg'];
      };

      $data = array();
      if (!empty($i['status_antrian'])) {
        $data['status_antrian'] =$i['status_antrian'];
      }
      $this->M_antrian->update($data,$where);
      $cek = $this->M_antrian->get_by_id($i['id_antrian']);
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
         $i = $this->input->delete();
      }
      $data = array(
        'iklan_tayang_id' => $i['id_iklan_tayang'],
      );
     
      $this->M_antrian->hapus($data);
      $cek = $this->M_antrian->get_by_id($data['id_iklan_tayang']);
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
