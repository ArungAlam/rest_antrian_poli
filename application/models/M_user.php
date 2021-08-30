<?php if(!defined('BASEPATH')) exit('No direct script allowed');

class M_user extends CI_Model{


  function get_user($q) {
		return $this->db->get_where('global.global_auth_user',$q);
	}
}