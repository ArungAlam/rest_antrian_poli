<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Monitor extends BD_Controller {
  function __construct() 
	{
		parent::__construct();
    $this->load->model('M_monitor');
    $this->load->model('M_ruangan');
    $this->load->model('M_jadwal_dokter','M_jadwal');
	}
  /***
   * status_pasien_tabel
      A  = sedang antri
      P  = Panggil Pasien 
      L  = Layani
      S  = Sudah di Layani  
    */ 

    public function pakai_ruangan_put()
    {    
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->put();
      }
      /* kosongan jadwal pakai ruang_id di jadwal dokter */
        $id_ruangan = $i['id_ruangan'];  
        $dataRuang = array(
           'id_ruangan' => NULL 
        );
        $this->M_monitor->kosongkan_ruangan($dataRuang,$id_ruangan);
      /* update ruangan  n */
        $dataRuang = array(
          'ruangan_id' => $id_ruangan,
          'is_ready'   => 'n'
         );
         $this->M_ruangan->update($dataRuang);
       /* set jadwal dokter */
         $dataRuang = array(
            'id_ruangan' => $id_ruangan
          );
          $where = array(
            'id_dokter' => $i['id_dokter'],
            'jadwal_dokter_hari'=> $i['hari'],
            'id_poli' => $i['id_poli']
          );
          $this->M_jadwal->update_where($dataRuang,$where);
        $cek = $this->M_jadwal->get_where($where);
        if($cek){
          $res = array (
            'msg' => 'ruangan berhasil di pakai',
            'success' => true,
          );
        }else{
          $res = array (
            'msg' => 'jadwal dokter tidak ditemukan',
            'success' => false,
          );

        }
        $this->set_response($res, REST_Controller::HTTP_OK);
    }

    public function kosongkan_ruangan_put()
    {    
      if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->put();
      }
        $id_ruangan = $i['id_ruangan'];      
      /* update ruangan  */
        $dataRuang = array(
          'ruangan_id' => $id_ruangan,
          'is_ready'   => 'y'
         );
         $this->M_ruangan->update($dataRuang);
       /* set jadwal dokter */
         $dataRuang = array(
            'id_ruangan' => Null
          );
          $where = array(
            'jadwal_dokter_id'=> $i['hari'],
          );
          $this->M_jadwal->update_where($dataRuang,$where);
        $cek = $this->M_jadwal->get_where($where);
        if($cek){
          $res = array (
            'msg' => 'ruangan berhasil di kosongkan',
            'success' => true,
          );
        }else{
          $res = array (
            'msg' => 'jadwal dokter tidak ditemukan',
            'success' => false,
          );

        }
        $this->set_response($res, REST_Controller::HTTP_OK);
    }

  public function poli_get()
	{    
    if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
      $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
    }else{
       $i = $this->input->get();
    }
    if($i['id_ruangan']){
      $param  = array('id_ruangan' => $i['id_ruangan']);
    }
     $res = $this->M_monitor->get_poli($param);
    $this->set_response($res, REST_Controller::HTTP_OK);
	}

  public function pasien_next_get()
	{    
    if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
      $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
    }else{
       $i = $this->input->get();
    }
     $skr = Date('Y-m-d');
     $param  = array( 
       'id_poli' =>$i['id_poli'],
       'id_dokter' =>$i['id_dokter'],
       'status_antrian' =>'A',
       'DATE(when_create)' => $skr,
     );
     $res = $this->M_monitor->get_pasien($param);
    $this->set_response($res, REST_Controller::HTTP_OK);
	}

  public function pasien_panggil_get()
	{    
    if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
      $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
    }else{
       $i = $this->input->get();
    }
     $skr = Date('Y-m-d');
     $param  = array( 
       'id_poli' =>$i['id_poli'],
       'id_dokter' =>$i['id_dokter'],
       'status_antrian' =>'P',
       'DATE(when_create)' => $skr,
     );
     $res = $this->M_monitor->get_pasien($param);
    $this->set_response($res, REST_Controller::HTTP_OK);
	}
  public function sedang_dilayani_get()
	{    
    if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->get();
      }
     $skr = Date('Y-m-d');
     $param  = array( 
       'id_poli' =>$i['id_poli'],
       'id_dokter' =>$i['id_dokter'],
       'status_antrian' =>'L',
       'DATE(when_create)' => $skr,
     );
     $res = $this->M_monitor->get_pasien($param);
    $this->set_response($res, REST_Controller::HTTP_OK);
	}
  public function sudah_dilayani_get()
	{    
    if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->input->get();
      }
     $skr = Date('Y-m-d');
     $param  = array( 
       'id_poli' =>$i['id_poli'],
       'id_dokter' =>$i['id_dokter'],
       'status_antrian' =>'L',
       'DATE(when_create)' => $skr,
     );
     $res = $this->M_monitor->count_pasien($param);
    $this->set_response($res, REST_Controller::HTTP_OK);
  }

}
