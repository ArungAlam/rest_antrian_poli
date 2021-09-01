<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Iklan extends BD_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->auth();  // fungsi dari bd controler /app/core/BD_Controller
        $this->load->model('M_iklan');
    }
	
    public function index_post()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->post();
      }

      // $uploadPath = base_url('upload/video_iklan/');
      $uploadPath =  './upload/video_iklan/';


    /* insert foto  */
         $jam_tayang = $i['jam_tayang'];
         $nama_iklan = $i['nama_iklan'];
         $hariTayang = explode(",",$i['hari_tayang']);
         $nama_video_upload = $_FILES["files"]["name"];
         $raw_name   = pathinfo( $_FILES["files"]["name"], PATHINFO_FILENAME );
         $cek_date   =  date("Ymdhis")."_".id_baru();
         $extension  = pathinfo( $_FILES["files"]["name"], PATHINFO_EXTENSION ); // jpg
         $basename   =  $cek_date.'_' .$raw_name . '.' . $extension; // 5dab1961e93a7_1571494241.jpg
         move_uploaded_file($_FILES['files']['tmp_name'], $uploadPath."".$basename); 

     $max =  $this->M_iklan->get_max();
     $urutan = $max->urut + 1;
   

      foreach ($hariTayang as $key => $val) {
        $data = array(
          'iklan_id' => id_baru(),
          'iklan_nama' => $nama_iklan,
          'iklan_video_nama' => $basename,
          'iklan_video_nama_upload' => $nama_video_upload,
          'iklan_tayang_hari' => $val,
          'iklan_tayang_jam' => $jam_tayang,
          'id_dep' => 9999999,
          'iklan_tayang_urut' =>$urutan + $key
        );
        $this->M_iklan->tambah($data);
      }
      if($data){
        $respone = array("success" => true );
      }else{
        $respone = array("success" => false );
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
      if (!empty($i['nama_iklan'])) {
        $where['UPPER(iklan_nama) like'] = '%%'.strtoupper($i['nama_iklan']).'%%';
      }
      if (!empty($i['jam_tayang'])) {
        $where['iklan_tayang_jam'] =$i['jam_tayang'];
      }
      if (!empty($i['hari_tayang'])) {
        $where['iklan_tayang_hari'] = $i['hari_tayang'];
      }
      $data = $this->M_iklan->get_all($where);
      $this->set_response($data, REST_Controller::HTTP_OK);

    }
    public function index_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->put();
      }
      $data = array(
        'iklan_id' => $i['id_iklan'],
        'iklan_tayang_urut' => $i['iklan_tayang_urut'],
      );
     
      $this->M_iklan->update($data);
      $cek = $this->M_iklan->get_by_id($data['iklan_id']);
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
      $this->set_response($data, REST_Controller::HTTP_OK);

    }
  
    public function index_delete()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->delete();
      }
      $data = array(
        'iklan_id' => $i['id_iklan'],
      );
     
      $this->M_iklan->delete($data);
      $cek = $this->M_iklan->get_by_id($data['iklan_id']);
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
      $this->set_response($data, REST_Controller::HTTP_OK);

    }

    


}
