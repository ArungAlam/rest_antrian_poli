<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_user extends CI_Model{


  function get_user($q) {
		return $this->db->get_where('global.global_auth_user',$q);
	}
  function get_user_full($q='') {
    $this->db->select('*');
    $this->db->from('global.global_auth_user a');
    $this->db->join('global.global_auth_role b', 'b.rol_id = a.id_rol', 'left');
    if($q){
    $this->db->where($q);
    }
    $query = $this->db->get();
    return $query->row();
	}
}