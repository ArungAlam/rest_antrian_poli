<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Antrian extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->auth();  // fungsi dari bd controler /app/core/BD_Controller
        $this->load->model('M_antrian');
        $this->load->model('M_user');
    }
	
    public function index_post()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->post();
      }         
       if(!$i['id_dokter']){
        $cek_param[] = "param id_dokter kosong";
       }
       if(!$i['id_reg']){
        $cek_param[] = "param id_reg kosong";
       }
       if(!$i['id_poli']){
        $cek_param[] = "param id_poli kosong";
       }
       if($cek_param){
        $respone = array(
          "msg" => $cek_param[0],
          "success" => false );
          $this->set_response($respone, REST_Controller::HTTP_OK);

       }else{
        $nomer = $this->get_nomer_antrian($i['id_dokter']);
        $id_baru = id_baru();
        $data = array(
          'antrian_id' => $id_baru,
          'id_reg' => $i['id_reg'],
          'id_poli' => $i['id_poli'],
          'id_dokter' => $i['id_dokter'],
          'status_antrian' => 'A',
          'no_antrian_pasien' => $nomer,
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
    }
    
    public function get_nomer_antrian($id_dokter = null)
    {
      $where1 = array(
        'b.when_create' => Date('Y-m-d'),
        'b.id_dokter' => $id_dokter,
        'a.jadwal_dokter_hari' => date('w'),
      );
      $where2 = array(
        'usr_id' => $id_dokter
      );
      $max = $this->M_antrian->get_max($where1);
      $nomer_antrian = $max->jml + 1;
      $data_dokter = $this->M_user->get_user_full($where2);
      $kode_dokter = $data_dokter->kode_nama;
      $nomer = strtoupper($kode_dokter)."-".$nomer_antrian;
      return $nomer;
    }
    public function index_get()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->get();
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
      if (!empty($i['custom_where'])) {
        $where_custom = $i['custom_where'] ."". " And DATE(when_create)='".date('Y-m-d')."'";
      }else{
        $where_custom = false;
      
      }
      // if (!empty($i['when_create'])) {
      //   $where['DATE(when_create)'] = $i['when_create'];
      // }
      if($where_custom){
        $data = $this->M_antrian->get_all($where_custom);
        $this->set_response($data, REST_Controller::HTTP_OK);

      }else{
        $where['DATE(when_create)'] = date('Y-m-d');
        $data = $this->M_antrian->get_all($where);
        $this->set_response($data, REST_Controller::HTTP_OK);
      }
      

    }
    public function list_get()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->get();
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
      $sqlwhere = implode(" and ",$where);
      $where_custom =  " ( status_antrian = 'A' or status_antrian ='P' ) and DATE(when_create)='".date('Y-m-d')."'"." and ".$sqlwhere;
      

      
      $where['DATE(when_create)'] = date('Y-m-d');
      $data = $this->M_antrian->get_all($where);
        $this->set_response($data, REST_Controller::HTTP_OK);
      

    }
    public function index_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->put();
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
         $i = $this->delete();
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
