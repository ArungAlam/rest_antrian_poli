<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_tayang extends CI_Model{




   /* dataTables */
   public function count($where=''){
    if (!empty($where)) {
      $this->db->where($where);
    }
    return $this->db->from('global.global_video_iklan_tayang')->get()->num_rows();
  }
  public function get_all($where='', $limit=0, $offset=0){
    $column_order = array('iklan_tayang_hari'); //set column field database for datatable orderable

  $this->db->select('iklan_tayang_id as id_iklan_tayang,
                     iklan_tayang_jam as jam_tayang,
                     iklan_tayang_hari as hari_tayang,id_iklan');
  $this->db->from('global.global_video_iklan_tayang');
  if (!empty($where)) {
    $this->db->where($where);
  }
  $this->db->order_by('iklan_tayang_hari', 'asc');
    if ($limit) {
      $this->db->limit($limit, $offset);
    }
      return $this->db->get()->result();
  }	
/* dataTables */

	public function listing ()
    {	
        $this->db->select('*');
        $this->db->from('global.global_video_iklan_tayang');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_by_id ($id = null)
    {	
        $this->db->select('*');
        $this->db->from('global.global_video_iklan_tayang');
        $this->db->where('iklan_tayang_id',$id);
        $query = $this->db->get();
        return $query->row();
    }
    public function get_max ()
    {	
        $this->db->select('max(iklan_tayang_urut) as urut');
        $this->db->from('global.global_video_iklan_tayang');
        $query = $this->db->get();
        return $query->row();
    }
    public function hapus($data = null)
		{
				$this->db->delete('global.global_video_iklan_tayang', $data);
		}

		public function tambah($data = null)
		{
				$this->db->insert('global.global_video_iklan_tayang', $data);
		}
    public function update($data = null,$where)
		{
				$this->db->where($where);
				$this->db->update('global.global_video_iklan_tayang', $data);

		}
}