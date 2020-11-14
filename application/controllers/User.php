<?php
class User extends CI_Controller
{
	function __construct()
	{
	  	parent::__construct();
	  	$this->load->model('User_model', 'user', TRUE);
	  	$this->load->model('User_level_model', 'user_level', TRUE);
	}
		  	  
	function index()
	{
		if ($this->session->userdata('login') != TRUE)
		{
			redirect('login');
		}
		else
		{		
		  	$this->load->helper('url');
			$data = array(
				'title' 			=> 'User', 
				'main_view' 		=> 'user/user', 
				'form_view' 		=> 'user/user_form',
				'table_controller' 	=> 'user'
			);

			$this->load->view('admin/template', $data);
		}	  	
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->user->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $user) {
			$no++;
			$aktif = '<label class="label label-danger">Tidak</label>';
			if ($user->user_aktif==1)
				$aktif = '<label class="label label-success">Aktif</label>';
			$row = array();
			$row[] = $user->user_name;
			$row[] = $user->user_level_name;
			$row[] = $aktif;
			
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" name="tombol_tambah" href="javascript:void(0)" title="Edit" onclick="edit_user('."'".$user->user_id."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
				  <a class="btn btn-sm btn-danger" name="tombol_hapus" href="javascript:void(0)" title="Hapus" onclick="delete_user('."'".$user->user_id."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->user->count_all(),
		"recordsFiltered" 	=> $this->user->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->user->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		
		$data = array(
		'user_full_name'=> $this->input->post('user_full_name', TRUE),
		'user_email'=> $this->input->post('user_email', TRUE),
		'user_name'=> $this->input->post('user_name', TRUE),
		'user_level_id'=> $this->input->post('user_level_id', TRUE),
		'user_aktif'=> $this->input->post('user_aktif', TRUE),
		'user_date_entri' => date('Y-m-d')
		);
		
		if ($this->input->post('user_password', TRUE)!="")
			$data['user_password'] = md5($this->input->post('user_password', TRUE));
		
		if(!empty($_FILES['user_photo']['name']))
		{
			$upload = $this->_do_upload();
			$data['user_photo'] = $upload;
		}
		
		$insert = $this->user->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'user_full_name'=> $this->input->post('user_full_name', TRUE),
		'user_email'=> $this->input->post('user_email', TRUE),
		'user_name'=> $this->input->post('user_name', TRUE),
		'user_level_id'=> $this->input->post('user_level_id', TRUE),
		'user_aktif'=> $this->input->post('user_aktif', TRUE),
		);
		
		if ($this->input->post('user_password', TRUE)!="")
			$data['user_password'] = md5($this->input->post('user_password', TRUE));

		if($this->input->post('remove_photo')) // if remove user_photo checked
		{
			if(file_exists('upload/user/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
			{
				@unlink('upload/user/'.$this->input->post('remove_photo'));
				@unlink('upload/user/thumbs/'.$this->input->post('remove_photo'));
			}
			$data['user_photo'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			
			//delete file
			$user = $this->user->get_by_id($this->input->post('id'));
			if(file_exists('upload/user/'.$user->user_photo) && $user->user_photo)
			{
				@unlink('upload/user/'.$user->user_photo);
				@unlink('upload/user/thumbs/'.$user->user_photo);
			}

			$data['user_photo'] = $upload;
		}
	
		$this->user->update(array('user_id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		
		//delete file
		$user = $this->user->get_by_id($id);
		if(file_exists('upload/user/'.$user->user_photo) && $user->user_photo)
		{
			@unlink('upload/user/'.$user->user_photo);
			@unlink('upload/user/thumbs/'.$user->user_photo);
		}
	
		$this->user->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	
	private function _do_upload()
	{
		$config['upload_path']    = 'upload/user/';
        $config['allowed_types']  = 'gif|jpg|png';
        $config['max_size']       = 1024; //set max size allowed in Kilobyte
        $config['max_width']      = 1024; // set max width image allowed
        $config['max_height']     = 1024; // set max height allowed
        $config['file_name']      = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		$file 		= $this->upload->data();
		$nama_file 	= $file['file_name'];					
									  
		
		$config = array(
			'source_image' 		=> $file['full_path'],
			'new_image' 		=> './upload/user/thumbs/',
			'maintain_ration' 	=> TRUE,
			'width' 			=> 100,
			'height' 			=> 100
		);
							
		$this->load->library('image_lib', $config);
		$this->image_lib->resize();	
							

		return $nama_file;
	}
	
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('user_name')=='')
		{
			$data['inputerror'][] = 'user_name';
			$data['error_string'][] = 'User Name is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('user_level_id')=='')
		{
			$data['inputerror'][] = 'user_level_id';
			$data['error_string'][] = 'Please select Level';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('user_aktif')=='')
		{
			$data['inputerror'][] = 'user_aktif';
			$data['error_string'][] = 'Please choose Aktif';
			$data['status'] = FALSE;							
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}	

	
	function ubah_password()
	{
		$id_pegawai = $this->input->post('id', TRUE);
		$password_lama = $this->input->post('password_lama', TRUE);
		$password_baru = $this->input->post('password_baru', TRUE);

		$validate = array();
		if ($password_lama=="") $validate[] = 'Masukkan password lama';
		if ($password_baru=="") $validate[] = 'Masukkan password baru';

		$jml_validate = count($validate);
		if ($jml_validate < 1)
		{
			$cek = $this->db->query("select * from user where id_pegawai='$id_pegawai' and user_password='".md5($password_lama)."'");
			if ($cek->num_rows() == 1)
			{
				$ubah_passwrod = $this->db->query("update user set user_password='".md5($password_baru)."' where id_pegawai='$id_pegawai'");
				print(json_encode(array("code" => 1, "message"=>"Password Berhasil Diubah.")));	
			}
			else
			{
				print(json_encode(array("code" => 0, "message"=>"Password salah.")));		
			}
		}
		else
		{
			print(json_encode(array("code" => 0, "message"=>"Password lama atau baru masih kosong.")));
		}
	}  
}
// END User Class
/* End of file user.php */
/* Location: ./sytem/application/controlers/user.php */		
