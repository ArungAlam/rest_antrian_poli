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
        'is_ready' =>'y'
      );
      $this->M_ruangan->tambah($data);
      $respone = array(
        'msg' => 'berhasil insert',
        'success' => true 
      );
      $this->set_response($respone, REST_Controller::HTTP_OK);
    }
    
    public function index_get()
    {
      /* deklarasi  */
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->get();
      }
      if($i['id_ruangan']){
            $data = $this->M_ruangan->get_by_id($i['id_ruangan']);
      }else{
          $where = array();
          if (!empty($i['ruangan_nama'])) {
            $where['UPPER(ruangan_nama) like'] = '%%'.strtoupper($i['ruangan_nama']).'%%';
          }
          if($i['is_ready']){
            $where['is_ready'] = $i['is_ready'];
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
        $respone = array('msg' => 'berhasil dihapus',
                        'success' => true );
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }else{
        $respone = array('msg' => 'data tidak di temukan',
                        'success' => false );
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }
    }

    public function index_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }
       $where = array(
         'ruangan_id' => $i['id_ruangan']
        );
        $data = array();
        if (!empty($i['ruangan_nama'])) {
          $data['ruangan_nama'] =$i['ruangan_nama'] ;
        }
        if(!empty($i['is_ready'])){
          $data['is_ready'] = $i['is_ready'];
        }
      $this->M_ruangan->update($data,$where);
      $cek = $this->M_ruangan->get_by_id($i['id_ruangan']);
      if($cek){
        $respone = array('msg' => 'berhasil dihapus',
                        'success' => true );
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }else{
        $respone = array('msg' => 'data tidak di temukan',
                        'success' => false );
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }
    }

    


}
