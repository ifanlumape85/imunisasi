<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @author		Ifan Lumape
 * @Email		fnnight@gmail.com.
 * @Start		22 April 2014
 * @Web			http://www.ifanlumape.com
 *
 */
class Login_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	var $table = 'v2_user';
	
	function check_user($username, $password)
	{
		$query = $this->db->get_where($this->table, array('user_name'=>$username, 
			'user_password' => $password, 'user_aktif'=> 1), 1, 0);
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

// END Login_model Class
/* End of file login_model.php */
/* Location: ./system/application/model/login_model */
?>