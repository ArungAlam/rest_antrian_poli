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
         $i = $this->put();
      }
      /* kosongan jadwal pakai ruang_id di jadwal dokter */
        $id_ruangan = $i['id_ruangan'];  
        $whereJadwal = array(
            'id_ruangan' => $id_ruangan
        );
        $dataRuang = array(
           'id_ruangan' => NULL 
        );
        $this->M_jadwal->update($dataRuang,$whereJadwal);
      /* update ruangan  n */
        $whereRuang = array(
          'ruangan_id' => $id_ruangan,
          );
        $dataRuang = array(
          'is_ready'   => 'n'
         );
         $this->M_ruangan->update($dataRuang,$whereRuang);
       /* set jadwal dokter */
         $dataJadwal = array(
            'id_ruangan' => $id_ruangan
          );
          $where = array(
            'id_dokter' => $i['id_dokter'],
            'jadwal_dokter_hari'=> $i['hari'],
            'id_poli' => $i['id_poli']
          );
          $where_cek = array(
            'id_dokter' => $i['id_dokter'],
            'jadwal_dokter_hari'=> $i['hari'],
            'a.id_poli' => $i['id_poli']
          );
          $this->M_jadwal->update($dataJadwal,$where);
        $cek = $this->M_jadwal->get_where($where_cek);
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
         $i = $this->put();
      }
        $id_ruangan = $i['id_ruangan'];      
      /* update ruangan  */
        $whereRuang = array(
           'ruangan_id' => $id_ruangan,
        );
        $dataRuang = array(
          'is_ready'   => 'y'
         );
         $this->M_ruangan->update($dataRuang,$whereRuang);
       /* set jadwal dokter */
         $dataRuang = array(
            'id_ruangan' => Null
          );
          $where = array(
            'id_dokter' => $i['id_dokter'],
            'jadwal_dokter_hari'=> $i['hari'],
            'id_poli' => $i['id_poli']
          );
          $where_cek = array(
            'id_dokter' => $i['id_dokter'],
            'jadwal_dokter_hari'=> $i['hari'],
            'a.id_poli' => $i['id_poli']
          );
          $this->M_jadwal->update($dataRuang,$where);
        $cek = $this->M_jadwal->get_where($where_cek);
        if(!$cek){
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
       $i = $this->get();
    }
    if($i['id_ruangan']){
      $param  = array('id_ruangan' => $i['id_ruangan']);
    }
    $res = $this->M_monitor->get_poli($param);
    $this->set_response($res, REST_Controller::HTTP_OK);
	}
  public function ruangan_dipakai_get()
	{    
     $res = $this->M_monitor->ruangan_dipakai();
     $this->set_response($res, REST_Controller::HTTP_OK);
	}

  public function pasien_next_get()
	{    
    if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
      $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
    }else{
       $i = $this->get();
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
       $i = $this->get();
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
         $i = $this->get();
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
         $i = $this->get();
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
  public function antrian_all_get()
	{    
    if( isset( $_SERVER['CONTENT_TYPE'] ) && strpos( $_SERVER['CONTENT_TYPE'], "application/json" ) !== false ){      
        $i = json_decode( trim( file_get_contents( 'php://input' ) ), true );
      }else{
         $i = $this->get();
      }
     $skr = date('Y-m-d');
     $where = "a.id_ruangan is NOT NULL";
     $raw = $this->M_monitor->antrian_all($where);
     $jml_ruang = count($raw);
     $data = [];
     for ($i = 0; $i < $jml_ruang; $i++) {
     $where_call = array(
       'status_antrian' => 'A',
       'id_dokter' => $raw[$i]['usr_id'],
       'id_poli'   => $raw[$i]['poli_id'],
       'DATE(when_create)' => $skr
     );
     $where_next = array(
      'status_antrian' => 'P',
      'id_dokter' => $raw[$i]['usr_id'],
      'id_poli'   => $raw[$i]['poli_id'],
      'DATE(when_create)' => $skr
    );
      $no_panggil = $this->M_monitor->get_no_antrian($where_call);
      $no_next = $this->M_monitor->get_no_antrian($where_next);
      $data[$i]['call'] = $no_panggil['no_antrian_pasien'];
      $data[$i]['next'] = $no_next['no_antrian_pasien'];
      $data[$i]['poli'] = $raw[$i]['poli_nama'];
      $data[$i]['dokter'] = substr($raw[$i]['usr_name'],0,20);
      $data[$i]['ruang'] = $raw[$i]['ruangan_nama'];
    }
    $this->set_response($data, REST_Controller::HTTP_OK);
  }

}
