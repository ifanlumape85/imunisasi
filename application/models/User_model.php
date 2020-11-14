<?php
class User_model extends CI_Model
{
	var $table = 'user';
	var $column_order = array('user_full_name','user_name','user_level_name',null); //set column field database for datatable orderable
	var $column_search = array('user_full_name','user_name','user_level_name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('user_id' => 'desc'); // default order 

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	private function _get_datatables_query($user_id)
	{	
		$this->db->select('user_id, user.user_name, user.user_level_id, user_aktif, user_photo, user_full_name, user_level.user_level_name');
		$this->db->join('user_level', 'user.user_level_id=user_level.user_level_id');
		$this->db->from($this->table);
		
		
		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					//$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
				{
					//$this->db->group_end(); //close bracket
				}
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($user_id="")
	{
		$this->_get_datatables_query($user_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($user_id="")
	{
		$this->_get_datatables_query($user_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($user_id="")
	{
		$this->db->from($this->table);
		
		if ($this->session->userdata('user_level_id') > 1)
		{
			if ($user_id!="")
				$this->db->where('user_id', $user_id);	
		}
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('user_id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_user()
	{
		$this->db->from($this->table);
		$query = $this->db->get();

		return $query->result();
	}	

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('user_id', $id);
		$this->db->delete($this->table);
	}	

	public function check_user($user_email, $user_password)
	{
		$query = $this->db->get_where($this->table, array('user_name' => $user_email, 'user_password' => $user_password), 1, 0);
		if ($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}		

	public function get_list_user($id="")
    {
        $this->db->from($this->table);
        $this->db->order_by('user_full_name','asc');
        if ($id!="")
        {
        	if (is_array($id))
        		$this->db->where($id);
        }
        $query = $this->db->get();
        $result = $query->result();

        $calegs = array();
        foreach ($result as $row) 
        {
            $calegs[$row->user_id] = $row->user_full_name;
        }
        return $calegs;
    }		

	public function get_user_by_user_name($user_email)
	{

		$this->db->from($this->table);
		$this->db->join('user_level','user.user_level_id=user_level.user_level_id');
		$this->db->where('user.user_name',$user_email);
		$query = $this->db->get();

		return $query->row();
	}						
}