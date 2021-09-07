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

      $uploadPath =  './upload/video_iklan/';


    /* insert foto  */
         $nama_iklan = $i['nama_iklan'];
         $nama_video_upload = $_FILES["files"]["name"];
         $raw_name   = pathinfo( $_FILES["files"]["name"], PATHINFO_FILENAME );
         $cek_date   =  date("Ymdhis")."_".id_baru();
         $extension  = pathinfo( $_FILES["files"]["name"], PATHINFO_EXTENSION ); // jpg
         $basename   =  $cek_date.'_' .$raw_name . '.' . $extension; // 5dab1961e93a7_1571494241.jpg
         move_uploaded_file($_FILES['files']['tmp_name'], $uploadPath."".$basename); 

     $max =  $this->M_iklan->get_max();
     $urutan = $max->urut + 1;
      
   
        $id_baru = id_baru();
        $data = array(
          'iklan_id' => $id_baru,
          'iklan_nama' => $nama_iklan,
          'iklan_video_nama' => $basename,
          'iklan_video_nama_upload' => $nama_video_upload,
          'id_dep' => 9999999,
      );
        $this->M_iklan->tambah($id_baru);
        $cek = $this->M_iklan->get_by_id($data);
      if($cek){
        $respone = array(
          "msg" => "data  berhasil di tambahkan",
          "success" => true );
      }else{
        $respone = array(
          "msg" => "data  berhasil di tambahkan",
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
      if (!empty($i['nama_iklan'])) {
        $where['UPPER(iklan_nama) like'] = '%%'.strtoupper($i['nama_iklan']).'%%';
      }
      
     
      $data = $this->M_iklan->get_all($where);
      $this->set_response($data, REST_Controller::HTTP_OK);

    }

    public function tipe_iklan_get()
    {

      $data = array(
        "T" => "Tambahan (hari besar)",
        "U" => "Utama (video Rs)",
        "I" => "Iklan",
      );
      $this->set_response($data, REST_Controller::HTTP_OK);
    }
    public function index_put()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->put();
      }
      $where = array(
        'iklan_id' => $i['id_iklan'],
      );
      $data = array();
      if (!empty($i['nama_iklan'])) {
        $data['iklan_nama'] = $i['id_iklan'];
      }
      $this->M_iklan->update($data,$where);
      $cek = $this->M_iklan->get_by_id($where['iklan_id']);
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
      };
      $uploadPath =  './upload/video_iklan/';
      $data = array(
        'iklan_id' => $i['id_iklan'],
      );
      $dataVideo = $this->M_iklan->get_by_id($data['iklan_id']);
      /* hapus video */
      unlink($uploadPath."/".$dataVideo->iklan_video_nama);
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
