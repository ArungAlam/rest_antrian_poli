<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ruangan extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // S$this->auth();  // fungsi dari bd controler /app/core/BD_Controller
        $this->load->model('M_ruangan');
    }
	
    public function index_post()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }
      $data = array(
        'ruangan_id' => id_baru(),
        'ruangan_nama' => $i['ruangan_nama'],
        'id_dep' => 9999999,
        'is_ready' =>'n'
      );
      $this->M_ruangan->tambah($data);
      $respone = array('status_db','sukses');
      $this->set_response($respone, REST_Controller::HTTP_OK);
    }
    
    public function index_get()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->get();
      }

      $id = ($i['id_ruangan'] != '') ? $i['id_ruangan'] : '' ;
      if($id){
            $data = $this->M_ruangan->get_by_id($id);
      }else{
          $where = array();
          if (!empty($i['ruangan_nama'])) {
            $where['UPPER(ruangan_nama) like'] = '%%'.strtoupper($i['ruangan_nama']).'%%';
          }
       $data = $this->M_ruangan->get_all($where, $i['length'], $i['start']);
      }
      if($data){
        $this->set_response($data, REST_Controller::HTTP_OK);
      }else{
        $this->set_response($data, REST_Controller::HTTP_NOT_FOUND);
      }
    }

    public function index_delete()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }

       $data = array(
         'ruangan_id' => $i['id_ruangan'],
       );
      $this->M_ruangan->hapus($data);
      $cek = $this->M_ruangan->get_by_id($i['id_ruangan']);
      if(!$cek){
        $respone = array('status_x' => 'berhasil_dihapus');
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }else{
        $respone = array('status_x' => 'ahhahahaha  gagal bro');
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }
    }

    public function index_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }
       $data = array(
         'ruangan_id' => $i['id_ruangan'],
         'ruangan_nama' => $i['ruangan_nama']
       );
      $this->M_ruangan->update($data);
      $cek = $this->M_ruangan->get_by_id($i['id_ruangan']);
      if($cek){
        $respone = array('status_x' => 'berhasil_diupdet');
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }else{
        $respone = array('status_x' => 'ahhahahaha  gagal bro');
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }
    }

    


}
