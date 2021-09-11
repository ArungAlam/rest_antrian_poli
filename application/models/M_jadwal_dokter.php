<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jadwal_dokter extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		#conf dept
		#session data
		#reqest
		
	}
    
   public function id_baru(){
    $this->db->select('max(ruangan_id) as max');
    $this->db->from('klinik.klinik_jadwal_dokter');
    $query = $this->db->get();
     $id=  $query->row()->max +1;
     return $id;
  }
  /* dataTables */
    public function count($where=''){
      if (!empty($where)) {
        $this->db->where($where);
      }
      return $this->db->from('klinik.klinik_jadwal_dokter')->get()->num_rows();
    }
    public function get_all($where='', $limit=false, $offset=false){
    $column_order = array('usr_name'); //set column field database for datatable orderable
    $this->db->select('a.*,b.usr_name,c.poli_nama,d.ruangan_nama');
    $this->db->from('klinik.klinik_jadwal_dokter a');
    $this->db->join('global.global_auth_user b','a.id_dokter = b.usr_id','left');
    $this->db->join('global.global_auth_poli c','a.id_poli = c.poli_id','left');
    $this->db->join('klinik.klinik_ruangan d','a.id_ruangan = d.ruangan_id','left');
    if (!empty($where)) {
      $this->db->where($where);
    }
    
    $this->db->order_by('usr_name', 'asc');
    if ($limit) {
      $this->db->limit($limit, $offset);
    }
      return $this->db->get()->result();
   }	
  /* dataTables */
  
   
    public function get_all_dokter ()
    {	
        $this->db->select('usr_name,usr_id as id_dokter');
        $this->db->from('global.global_auth_user a');
        $this->db->join('global.global_auth_role b', 'a.id_rol=b.rol_id' ,'left');
        $this->db->where('rol_jabatan','D');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_all_ruangan ()
    {	
        $this->db->select('*');
        $this->db->from('klinik.klinik_ruangan a');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_all_poli ()
    {	
        $this->db->select('*,poli_id as id_poli');
        $this->db->from('global.global_auth_poli');
        $this->db->where('poli_tipe','J');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_by_id ($id)
    {	
        $this->db->select('*');
        $this->db->from('klinik.klinik_jadwal_dokter');
        $this->db->where('jadwal_dokter_id',$id);
        $query = $this->db->get();
        return $query->row();
    }
    public function hapus($data)
		{
				$this->db->delete('klinik.klinik_jadwal_dokter', $data);
		}

		public function tambah($data)
		{
				$this->db->insert('klinik.klinik_jadwal_dokter', $data);
		}
    public function update($data,$where)
		{
				$this->db->where($where);
				$this->db->update('klinik.klinik_jadwal_dokter', $data);
		}
    public function get_where ($where='')
    {	
        $this->db->select('*');
        $this->db->from('klinik.klinik_jadwal_dokter a');
        $this->db->join('global.global_auth_user b', 'a.id_dokter=b.usr_id' ,'left');
        if($where){
          $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();
    }
}
