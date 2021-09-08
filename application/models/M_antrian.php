<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_antrian extends CI_Model{




   /* dataTables */
   public function count($where=''){
    if (!empty($where)) {
      $this->db->where($where);
    }
    return $this->db->from('klinik.klinik_nomer_antrian')->get()->num_rows();
  }
  public function get_all($where='', $limit=0, $offset=0){
    $this->db->select('*,antrian_id as id_antrian');
    $this->db->from('klinik.klinik_nomer_antrian');
  if (!empty($where)) {
    $this->db->where($where);
  }
  $this->db->order_by('when_create', 'asc');
    if ($limit) {
      $this->db->limit($limit, $offset);
    }
      return $this->db->get()->result();
  }	
/* dataTables */

	public function listing ()
    {	
        $this->db->select('*');
        $this->db->from('klinik.klinik_nomer_antrian');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_by_id ($id = null)
    {	
        $this->db->select('*');
        $this->db->from('klinik.klinik_nomer_antrian');
        $query = $this->db->get();
        return $query->row();
    }
    public function get_max ()
    {	
        $this->db->select('max(iklan_tayang_urut) as urut');
        $this->db->from('klinik.klinik_nomer_antrian');
        $query = $this->db->get();
        return $query->row();
    }
    public function hapus($data = null)
		{
				$this->db->delete('klinik.klinik_nomer_antrian', $data);
		}

		public function tambah($data = null)
		{
				$this->db->insert('klinik.klinik_nomer_antrian', $data);
		}
    public function update($data = null,$where)
		{
				$this->db->where($where);
				$this->db->update('klinik.klinik_nomer_antrian', $data);

		}
}