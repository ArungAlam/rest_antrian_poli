<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_ruangan extends CI_Model{




   /* dataTables */
   public function count($where=''){
    if (!empty($where)) {
      $this->db->where($where);
    }
    return $this->db->from('klinik.klinik_ruangan')->get()->num_rows();
  }
  public function get_all($where='', $limit=0, $offset=0){
    $column_order = array('ruangan_nama'); //set column field database for datatable orderable

  $this->db->select('*');
  $this->db->from('klinik.klinik_ruangan');
  if (!empty($where)) {
    $this->db->where($where);
  }
  $this->db->order_by('ruangan_nama', 'asc');
    if ($limit) {
      $this->db->limit($limit, $offset);
    }
      return $this->db->get()->result();
  }	
/* dataTables */

	public function listing ()
    {	
        $this->db->select('*');
        $this->db->from('klinik.klinik_ruangan');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_by_id ($id = null)
    {	
        $this->db->select('*');
        $this->db->from('klinik.klinik_ruangan');
        if($id){        
          $this->db->where('ruangan_id',$id);
        }
        $query = $this->db->get();
        return $query->row();
    }
    public function hapus($data = null)
		{
				$this->db->delete('klinik.klinik_ruangan', $data);
		}

		public function tambah($data = null)
		{
				$this->db->insert('klinik.klinik_ruangan', $data);
		}
    public function update($data = null)
		{
				$this->db->where('ruangan_id', $data['ruangan_id']);
				$this->db->update('klinik.klinik_ruangan', $data);

		}
}