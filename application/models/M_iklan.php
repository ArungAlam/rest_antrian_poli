<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_iklan extends CI_Model{




   /* dataTables */
   public function count($where=''){
    if (!empty($where)) {
      $this->db->where($where);
    }
    return $this->db->from('global.global_video_iklan')->get()->num_rows();
  }
  public function get_all($where='', $limit=0, $offset=0){
    $column_order = array('ruangan_nama'); //set column field database for datatable orderable

  $this->db->select('*');
  $this->db->from('global.global_video_iklan');
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
        $this->db->from('global.global_video_iklan');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_by_id ($id = null)
    {	
        $this->db->select('*');
        $this->db->from('global.global_video_iklan');
        if($id){        
          $this->db->where('ruangan_id',$id);
        }
        $query = $this->db->get();
        return $query->row();
    }
    public function get_max ($id = null)
    {	
        $this->db->select('max(iklan_tayang_urut) as urut');
        $this->db->from('global.global_video_iklan');
        if($id){        
          $this->db->where('ruangan_id',$id);
        }
        $query = $this->db->get();
        return $query->row();
    }
    public function hapus($data = null)
		{
				$this->db->delete('global.global_video_iklan', $data);
		}

		public function tambah($data = null)
		{
				$this->db->insert('global.global_video_iklan', $data);
		}
    public function update($data = null)
		{
				$this->db->where('iklan_id', $data['iklan_id']);
				$this->db->update('global.global_auth_role', $data);

		}
}