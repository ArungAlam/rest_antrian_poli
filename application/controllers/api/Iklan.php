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
         $i = $this->post();
      }

      $uploadPath =  './upload/video_iklan/';


    /* insert foto  */
         $nama_iklan = $i['iklan_nama'];
       if(empty($_FILES) && emtpy($i['files'])){
          $cek_param[] = "file tidak ada"; 
        }elseif(!empty($_FILES) ) {
         $nama_video_upload = $_FILES["files"]["name"];
         $raw_name   = pathinfo( $_FILES["files"]["name"], PATHINFO_FILENAME );
         $cek_date   =  date("Ymdhis")."_".id_baru();
         $extension  = pathinfo($_FILES["files"]["name"], PATHINFO_EXTENSION ); // jpg
         $basename   =  $cek_date.'_' .$raw_name . '.' . $extension; // 5dab1961e93a7_1571494241.jpg
         move_uploaded_file($_FILES['files']['tmp_name'], $uploadPath."".$basename); 
         $id_dep = ($i['id_dep']) ? $i['id_dep'] :'9999999'; 
         $id_baru = id_baru();
         $data = array(
          'iklan_id' => $id_baru,
          'iklan_video_nama' => $basename,
          'iklan_video_nama_upload' => $nama_video_upload,
          'id_dep' => $id_dep ,
         );
        }elseif(!empty($i['files']) ) {
          $nama_video_upload = $i["files"]["name"];
          $raw_name   = pathinfo( $i["files"]["name"], PATHINFO_FILENAME );
          $cek_date   =  date("Ymdhis")."_".id_baru();
          $extension  = pathinfo($i["files"]["name"], PATHINFO_EXTENSION ); // jpg
          $basename   =  $cek_date.'_' .$raw_name . '.' . $extension; // 5dab1961e93a7_1571494241.jpg
          move_uploaded_file($i['files']['tmp_name'], $uploadPath."".$basename); 
          $id_dep = ($i['id_dep']) ? $i['id_dep'] :'9999999'; 
          $id_baru = id_baru();
          $data = array(
           'iklan_id' => $id_baru,
           'iklan_video_nama' => $basename,
           'iklan_video_nama_upload' => $nama_video_upload,
           'id_dep' => $id_dep ,
          );
        }
        
     
      if (!empty($i['iklan_tipe'])) {
          $data['iklan_tipe'] = $i['iklan_tipe'];
      }else{
        $cek_param[] = "iklan_tipe kosong";
      }
      if (!empty($i['iklan_nama'])) {
        $data['iklan_nama'] = $i['iklan_nama'];
      }else{
        $cek_param[] = "iklan_tipe kosong";
      }

      if(!empty($cek_param)){
         $respone = array(
          "msg" => $cek_param[0],
          "data" => $i,
          "success" => false );
          $this->set_response($respone, REST_Controller::HTTP_CREATED);
      }else{
        $this->M_iklan->tambah($data);
        $cek = $this->M_iklan->get_by_id($id_baru);
      if($cek){
        $respone = array(
          "msg" => "data  berhasil di tambahkan",
          "success" => true );
      }else{
        $respone = array(
          "msg" => "data  gagal  di tambahkan",
          "success" => false );
      }
      $this->set_response($respone, REST_Controller::HTTP_CREATED);
    }
      
    }
    
    public function index_get()
    {
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
         $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->get();
      }
      $where = array();
      if (!empty($i['iklan_nama'])) {
        $where['UPPER(iklan_nama) like'] = '%%'.strtoupper($i['iklan_nama']).'%%';
      }
      if (!empty($i['iklan_tipe'])) {
        $where['iklan_tipe'] = $i['iklan_tipe'];
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
         $i = $this->put();
      }
      $where = array(
        'iklan_id' => $i['id_iklan'],
      );
      $data = array();
      if (!empty($i['iklan_nama'])) {
        $data['iklan_nama'] = $i['iklan_nama'];
      }
      if (!empty($i['iklan_tipe'])) {
        $data['iklan_tipe'] = $i['iklan_tipe'];
      }
      /* perbarui video */
      if (!empty($i['files'])) {
        $data['iklan_tipe'] = $i['iklan_tipe'];

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
         $i = $this->delete();
      };
      $uploadPath =  './upload/video_iklan/';
      $data = array(
        'iklan_id' => $i['id_iklan'],
      );
      $dataVideo = $this->M_iklan->get_by_id($data['iklan_id']);
      /* hapus video */
      $cFile= $uploadPath."".$dataVideo->iklan_video_nama;
      if(file_exists($cFile)){
        unlink($uploadPath."".$dataVideo->iklan_video_nama);
      };
      $this->M_iklan->hapus($data);
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
      $this->set_response($respone, REST_Controller::HTTP_OK);

    }

    


}
