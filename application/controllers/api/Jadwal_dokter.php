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
        'jadwal_dokter_hari' => $i['hari'],
        'jadwal_dokter_jam_mulai' => $i['jam_mulai'],
        'jadwal_dokter_jam_selesai' => $i['jam_selesai'],
        'id_poli' => $i['id_poli'],
        'id_ruangan' => $i['id_ruangan'],
        'id_dokter' => $i['id_dokter'],
      );
      $this->M_jadwal->tambah($data);
      $respone = array('status_db','sukses');
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
          if (!empty($i['ruangan_nama'])) {
            $where['UPPER(ruangan_nama) like'] = '%%'.strtoupper($i['ruangan_nama']).'%%';
          }
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
      $data = $this->M_jadwal-> get_all_dokter();
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
         'jadwal_dokter_id' => $i['jadwal_dokter_id'],
       );
      $this->M_jadwal->hapus($data);
      $cek = $this->M_jadwal->get_by_id($i['id_ruangan']);
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
      }else{
         $i = $this->input->put();
      }
      $data = array(
            'jadwal_dokter_id' => $i['id_jadwal_dokter'],
            'jadwal_dokter_hari' => $i['hari'],
            'jadwal_dokter_jam_mulai' => $i['jam_mulai'],
            'jadwal_dokter_jam_selesai' => $i['jam_selesai'],
            'id_poli' => $i['id_poli'],
            'id_ruangan' => $i['id_ruangan'],
            'id_dokter' => $i['id_dokter'],
      );
      $this->M_jadwal->update($data);
      $cek = $this->M_jadwal->get_by_id($i['id_jadwal_dokter']);
      if($cek){
        $respone = array('status_x' => 'berhasil_diupdet');
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }else{
        $respone = array('status_x' => 'ahhahahaha  gagal bro');
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
        $respone = array('status_x' => 'berhasil_diupdet');
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }else{
        $respone = array('status_x' => 'ahhahahaha  gagal bro');
        $this->set_response($respone, REST_Controller::HTTP_OK);
      }
    }

    


}
