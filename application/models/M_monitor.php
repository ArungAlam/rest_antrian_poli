<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_monitor extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	
		
	}
    
   public function get_poli(){
    $this->db->select("poli_id,poli_nama");
    $this->db->from('global.global_auth_poli d');
    $this->db->where('poli_tipe','J');
     $query = $this->db->get();
    return $query->result_array();
    
   }
   public function count_pasien($where){
    $this->db->select("count(no_antrian_pasien) as jml");
    $this->db->from('klinik.klinik_nomer_antrian');
    $this->db->where($where);
    $query = $this->db->get();
    return $query->result();
   }
   public function get_pasien($where){
    $this->db->select("no_antrian_pasien");
    $this->db->from('klinik.klinik_nomer_antrian');
    $this->db->where($where);
    $this->db->order_by('when_create', 'ASC');
    $this->db->limit(1,0);
    $query = $this->db->get();
    return $query->result();
   }

   
   public function antrian_all($where = null)
   {

    $this->db->select("c.usr_name ,d.poli_nama,d.poli_id ,usr_id,ruangan_nama");
    $this->db->from('klinik.klinik_jadwal_dokter a');
    $this->db->join('global.global_auth_user c ', 'c.usr_id = a.id_dokter', 'left');
    $this->db->join('global.global_auth_poli d ', 'd.poli_id = a.id_poli', 'left');
    $this->db->join('klinik.klinik_ruangan e ', ' e.ruangan_id = a.id_ruangan', 'left');
    if($where){
    $this->db->where($where);
   }
    $this->db->order_by('ruangan_nama', 'ASC');
    $query = $this->db->get();
    return $query->result_array();
   }
   public function get_no_antrian($where = null)
   {

    $this->db->select("no_antrian_pasien");
    $this->db->from('klinik.klinik_nomer_antrian a');
    if($where){
    $this->db->where($where);
    }
    $this->db->order_by('no_antrian_pasien', 'DESC');
    $query = $this->db->get();
    return $query->row_array();
   }
   public function ruangan_dipakai()
   {
    $this->db->select("a.ruangan_id,a.ruangan_nama,c.usr_name ,d.poli_nama ,
                        b.jadwal_dokter_id , a.is_ready ,b.id_ruangan,b.id_dokter,b.id_poli");
                        
    $this->db->from('klinik.klinik_ruangan a'); 
    $this->db->join('klinik.klinik_jadwal_dokter b ', 'b.id_ruangan = a.ruangan_id', 'left');
    $this->db->join('global.global_auth_user c ', 'c.usr_id = b.id_dokter', 'left');
    $this->db->join('global.global_auth_poli d ', 'd.poli_id = b.id_poli', 'left');
    $this->db->order_by('a.ruangan_nama', 'ASC');
    $query = $this->db->get();
    return $query->result();
   }
}
