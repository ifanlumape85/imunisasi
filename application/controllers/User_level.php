<?php
class User_level extends CI_Controller
{
	function __construct()
	{
	  	parent::__construct();
	  	$this->load->model('User_level_model', 'user_level', TRUE);
	}
		  	  
	function index()
	{
	  	$this->load->helper('url');
		$data = array(
			'title' 	=> 'User Level', 
			'main_view' => 'user_level/user_level', 
			'form_view' => 'user_level/user_level_form',
		);
		$this->load->view('admin/template', $data);	  	
	}

	public function test()
	{
		$menu = $this->menu_sub->get_list_menu_sub(1);
		echo $menu->num_rows();
	}

	public function ajax_list()
	{
		$list = $this->user_level->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user_level) {
			$no++;
			$row = array();
			$row[] = $user_level->user_level_name;
			$row[] = $user_level->user_level_description;
	
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_user_level('."'".$user_level->user_level_id."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_user_level('."'".$user_level->user_level_id."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->user_level->count_all(),
		"recordsFiltered" 	=> $this->user_level->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->user_level->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		 	$data = array(
'user_level_name'=> $this->input->post('user_level_name', TRUE),
'user_level_description'=> $this->input->post('user_level_description', TRUE),
'user_id_entri'=> $this->session->userdata('user_id'),
'user_level_date_entri'=> date('Y-m-d'), 
'user_id_update'=> $this->session->userdata('user_id'),
'user_level_date_update'=> date('Y-m-d'), 
);	
		$insert = $this->user_level->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		 	$data = array(
'user_level_name'=> $this->input->post('user_level_name', TRUE),
'user_level_description'=> $this->input->post('user_level_description', TRUE),
'user_id_entri'=> $this->session->userdata('user_id'),
'user_level_date_entri'=> date('Y-m-d'), 
'user_id_update'=> $this->session->userdata('user_id'),
'user_level_date_update'=> date('Y-m-d'), 
);
		$this->user_level->update(array('user_level_id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->user_level->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		
		if ($this->input->post('user_level_name')=='')
		{
			$data['inputerror'][] = 'user_level_name';
			$data['error_string'][] = 'User Level Name  is required';
			$data['status'] = FALSE;							
		}
		if ($this->input->post('user_level_description')=='')
		{
			$data['inputerror'][] = 'user_level_description';
			$data['error_string'][] = 'User Level Description  is required';
			$data['status'] = FALSE;							
		}
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}		  
}
// END User_level Class
/* End of file user_level.php */
/* Location: ./sytem/application/controlers/user_level.php */		
