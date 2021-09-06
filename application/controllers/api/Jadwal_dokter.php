<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_dokter extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->auth();  // fungsi dari bd controler /app/core/BD_Controller
        $this->load->model('M_jadwal_dokter','M_jadwal');
    }
	
    public function index_post()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->post();
      }
      $data = array(
        'jadwal_dokter_id' => id_baru(),
      );
      if (!empty($i['hari'])) {
        $data['jadwal_dokter_hari'] =$i['hari'] ;
      }
      if(!empty($i['jam_mulai'])){
        $data['jadwal_dokter_jam_mulai'] = $i['jam_mulai'];
      }
      if(!empty($i['jam_selesai'])){
       $data['jadwal_dokter_jam_selesai'] = $i['jam_selesai'];
      }
      if(!empty($i['id_poli'])){
       $data['id_poli'] = $i['id_poli'];
      }
      if(!empty($i['id_ruangan'])){
       $data['id_ruangan'] = $i['id_ruangan'];
      }
      if(!empty($i['id_dokter'])){
       $data['id_dokter'] = $i['id_dokter'];
      } 
      $this->M_jadwal->tambah($data);
      $respone = array(
        'msg' => 'berhasil insert',
        'success' => true 
      );
      $this->set_response($respone, REST_Controller::HTTP_OK);
    }
    
    public function index_get()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->get();
      }

      $id = ($i['id_jadwal_dokter'] != '') ? $i['id_jadwal_dokter'] : '' ;
      if($id){
            $data = $this->M_jadwal->get_by_id($id);
      }else{
          $where = array();
          if($i['id_poli']){
            $where['a.id_poli'] = $i['id_poli'];
          };
          if($i['hari']){
            $where['jadwal_dokter_hari'] = $i['hari'];
          };
        $data = $this->M_jadwal->get_all($where, $i['length'], $i['start']);
      }
       $this->set_response($data, REST_Controller::HTTP_OK);
    }
    public function hari_get(){
      $arrHari = array(
        '0' => 'minggu',
        '1' => 'senin',
        '2' => 'selasa',
        '3' => 'rabu',
        '4' => 'kamis',
        '5' => 'jumat',
        '6' => 'sabtu',
      );
      $this->set_response($arrHari, REST_Controller::HTTP_OK);
    }
    public function dokter_get(){
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->get();
      }
      $where = array();
      if($i['id_poli']){
        $where['a.id_poli'] = $i['id_poli'];
      };
      if($i['hari']){
        $where['jadwal_dokter_hari'] = $i['hari'];
      };
       
      $data = $this->M_jadwal->get_where($where);
      $this->set_response($data, REST_Controller::HTTP_OK);
    }
    public function poli_get(){
      $dataPoli =$this->M_jadwal->get_all_poli();
      $this->set_response($dataPoli, REST_Controller::HTTP_OK);
    }

    public function index_delete()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->delete();
      }

       $data = array(
         'jadwal_dokter_id' => $i['id_jadwal_dokter'],
       );
      $this->M_jadwal->hapus($data);
      $cek = $this->M_jadwal->get_by_id($i['id_ruangan']);
      if(!$cek){
        $respone = array(
          'msg' => 'berhasil dihapus',
          'success' => true 
        );
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }else{
        $respone = array(
          'msg' => 'data tidak di temukan',
          'success' => false 
        );
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }
    }

    public function index_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->put();
      }
      
       $where = array(
        'jadwal_dokter_id' => $i['id_jadwal_dokter']
       );
       $data = array();
       if (!empty($i['hari'])) {
         $data['jadwal_dokter_hari'] =$i['hari'] ;
       }
       if(!empty($i['jam_mulai'])){
         $data['jadwal_dokter_jam_mulai'] = $i['jam_mulai'];
       }
       if(!empty($i['jam_selesai'])){
        $data['jadwal_dokter_jam_selesai'] = $i['jam_selesai'];
       }
       if(!empty($i['id_poli'])){
        $data['id_poli'] = $i['id_poli'];
       }
       if(!empty($i['id_ruangan'])){
        $data['id_ruangan'] = $i['id_ruangan'];
       }
       if(!empty($i['id_dokter'])){
        $data['id_dokter'] = $i['id_dokter'];
       } 
      $this->M_jadwal->update($data,$where);
      $cek = $this->M_jadwal->get_by_id($i['id_jadwal_dokter']);
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
    public function set_ruangan_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->put();
      }
      $data = array(
            'jadwal_dokter_id' => $i['id_jadwal_dokter'],
            'id_ruangan' => $i['id_ruangan'],
      );
      $this->M_jadwal->update($data);
      $cek = $this->M_jadwal->get_by_id($i['id_jadwal_dokter']);
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
