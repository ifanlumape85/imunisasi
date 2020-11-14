<?php
class Video extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Video_model', 'video', TRUE);
		$this->load->model('User_model', 'user', TRUE);
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
			'title' 	=> 'Data Video', 
			'main_view' => 'video/video', 
			'form_view' => 'video/video_form',
			);

			$users = $this->user->get_list_user();		
			$opt_user = array('' => '-- Pilih --');
		    foreach ($users as $i => $v) {
		        $opt_user[$i] = $v;
		    }

		    $data['form_user'] = form_dropdown('id_user',$opt_user,'','id="id_user" class="form-control"');
			$data['form_user2'] = form_dropdown('id_user2',$opt_user,'','id="id_user2" class="form-control"');
			$data['options_user'] = $opt_user;
			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->video->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $video) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$video->id_video.'">';
			$row[] = $no;
			$row[] = $video->user_full_name; 
			$row[] = $video->judul_video; 
			$row[] = tgl_indonesia2($video->tgl_input); 
			$row[] = tgl_indonesia2($video->tgl_update); 
						
			 if($video->video)
			 	$row[] = '<a href="'.base_url('upload/video/'.$video->video).'" target="_blank">'.$video->video.'</a>';
			 else
			 	$row[] = '(No video)';
			//add html for action
			$row[] = '<a class="btn btn-sm btn-warning btn-flat" href="javascript:void(0)" title="Edit" onclick="thumbnail_video('."'".$video->id_video."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Thumbnail</a><a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_video('."'".$video->id_video."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_video('."'".$video->id_video."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->video->count_all(),
		"recordsFiltered" 	=> $this->video->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->video->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'id_user'=> 1,//$this->session->userdata('user_id'),
		'judul_video'=> $this->input->post('judul_video', TRUE),
		'deskripsi'=> $this->input->post('deskripsi', TRUE),
		'tgl_input'=> date('Y-m-d'),
		'tgl_update'=> date('Y-m-d'),
		);
				
		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
		 	$data['video'] = $upload;
		}
		$insert = $this->video->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'id_user'		=> $this->session->userdata('user_id'),
		'judul_video'	=> $this->input->post('judul_video', TRUE),
		'deskripsi'		=> $this->input->post('deskripsi', TRUE),
		'tgl_update'	=> date('Y-m-d'),
		);		
		
		if($this->input->post('remove_photo')) // if remove photo checked
		{
		 	if(file_exists('upload/video/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
		 	{
				@unlink('upload/video/'.$this->input->post('remove_photo'));
		 		@unlink('upload/video/thumbs/'.$this->input->post('remove_photo'));
		 	}
		 	$data['video'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
			
		 	//delete file
		 	// $video = $this->video->get_by_id($this->input->post('id'));
		 	// if(file_exists('upload/video/'.$video->video) && $video->video)
		 	// {
		 	// 	@unlink('upload/video/'.$video->video);
		 	// 	@unlink('upload/video/thumbs/'.$video->video);
		 	// }

		 	$data['video'] = $upload;
		}
		$this->video->update(array('id_video' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_thumbnail()
	{
		// $this->_validate();
		
		$data = array(
		'tgl_update'=> date('Y-m-d'),
		);		
		
		if($this->input->post('remove_thumbnail')) // if remove photo checked
		{
		 	if(file_exists('upload/video/thumbs/'.$this->input->post('remove_thumbnail')) && $this->input->post('remove_thumbnail'))
		 	{
				@unlink('upload/video/thumbs/'.$this->input->post('remove_thumbnail'));
		 	}
		 	$data['thumbnail'] = '';
		}

		if(!empty($_FILES['thumbnail']['name']))
		{
		 	$upload = $this->_thumbnail_upload();
			
		 	//delete file
		 	$video = $this->video->get_by_id($this->input->post('id_video'));
		 	if(file_exists('upload/video/thumbs/'.$video->thumbnail) && $video->thumbnail)
		 	{
		 		@unlink('upload/video/thumbs/'.$video->thumbnail);
		 	}

		 	$data['thumbnail'] = $upload;
		}
		$this->video->update(array('id_video' => $this->input->post('id_video')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{	
		$video = $this->video->get_by_id($id);
		if(file_exists('upload/video/'.$video->video) && $video->video)
		{
		 	@unlink('upload/video/'.$video->video);
		 	@unlink('upload/video/thumbs/'.$video->video);
		}
		$this->video->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->video->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }	

	public function _do_upload()
	{
	 	$config['upload_path']    = 'upload/video/';
        $config['allowed_types']  = 'mp4';
        $config['max_size']       = 102400; //set max size allowed in Kilobyte
        // $config['max_width']      = 1000; // set max width image allowed
        // $config['max_height']     = 1000; // set max height allowed
        $config['file_name']      = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        	// echo 'nda jadi';
        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
	 		$data['error_string'][] = 'Upload error: '.$_FILES['photo']['type'].' '.$this->upload->display_errors('',''); //show ajax error
	 		$data['status'] = FALSE;
	 		echo json_encode($data);
	 		exit();
	 	}
	 	$file 		= $this->upload->data();
	 	$nama_file 	= $file['file_name'];					
									  
	 	return $nama_file;
	}

	public function _thumbnail_upload()
	{
	 	$config['upload_path']    = 'upload/video/thumbs';
        $config['allowed_types']  = 'jpg|jpeg';
        $config['max_size']       = 10240; //set max size allowed in Kilobyte
        $config['max_width']      = 1000; // set max width image allowed
        $config['max_height']     = 1000; // set max height allowed
        $config['file_name']      = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        	// echo 'nda jadi';
        if(!$this->upload->do_upload('thumbnail')) //upload and validate
        {
            $data['inputerror'][] = 'thumbnail';
	 		$data['error_string'][] = 'Upload error: '.$_FILES['thumbnail']['type'].' '.$this->upload->display_errors('',''); //show ajax error
	 		$data['status'] = FALSE;
	 		echo json_encode($data);
	 		exit();
	 	}
	 	$file 		= $this->upload->data();
	 	$nama_file 	= $file['file_name'];					
									  
	 	return $nama_file;
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

		if ($this->input->post('judul_video')=='')
		{
			$data['inputerror'][] = 'judul_video';
			$data['error_string'][] = 'Judul is required';
			$data['status'] = FALSE;							
		}
		
		if ($this->input->post('deskripsi')=='')
		{
			$data['inputerror'][] = 'deskripsi';
			$data['error_string'][] = 'Deskripsi is required';
			$data['status'] = FALSE;							
		}
		
		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	
	function preview($id)
	{
		$this->load->library('Pdf');
		// $dokumens = $this->db->query("SELECT * FROM video WHERE photo='".encode($id)."'");

		if (file_exists('upload/video/'.$id))
		{
			$file_pdf 		= $id;
			$landscape 		= FALSE;
			$potrait 		= FALSE;
			$pdf 			= new Pdf();
			// add a page
			// get the page count
			$pageCount = $pdf->setSourceFile('upload/video/'.$file_pdf);
			// iterate through all pages
			for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) 
			{
				$templateId = $pdf->importPage($pageNo);
			    $size = $pdf->getTemplateSize($templateId);

				if ($size['width'] > $size['height']) 
			    {
			    	$landscape = TRUE;
				    $pdf->AddPage('L', array($size['width'], $size['height']));
				} 
				else 
				{
					$potrait = TRUE;
				    $pdf->AddPage('P', array($size['width'], $size['height']));
				}
			    
			    $pdf->useTemplate($templateId, ['adjustPageSize' => true]);
			    $pdf->SetMargins(0, 0, 0);
			    $pdf->SetFont('Times','', 11);
			}

			// Output the new PDF
			// $pdf->Output("upload/dokumen/thumbs/".$file_pdf, "F");
			$pdf->Output();               
		}
	}

	function list_video()
	{
      	$query=$_POST['query'];
		$limit=$_POST['limit'];
        $start=$_POST['start'];

        // $query='';
        // $limit=10;
        // $start=0;

		$sql="SELECT video.*, user.user_full_name, user.user_photo FROM video LEFT JOIN user ON video.id_user=user.user_id ";

        if ($query!='')
        {
            $sql .= " WHERE video.judul_video LIKE '%$query%' ";
        }

        $sql .= " ORDER BY video.tgl_input DESC LIMIT $limit OFFSET $start ";
       
       $qry = $this->db->query($sql);
       if ($qry->num_rows() > 0)
       {

    		$results=array();
            foreach($qry->result() as $row)
            {
                array_push($results, 
                    array(
                    "id"         => $row->id_video,
                    "judul_video"=> $row->judul_video,
                    "thumbnail"  => $row->thumbnail,
                    "deskripsi"  => $row->deskripsi,
                    "video"      => $row->video,
                    "id_user"    => $row->user_photo,
                    "tgl_input"  => tgl_indonesia2($row->tgl_input),
                    "tgl_update" => tgl_indonesia2($row->tgl_update)
                    )
                );
            }
            print(json_encode(array("code" => 1, "message"=>"Success", "videos"=>$results)));
       }
       else
       {
       	print(json_encode(array("code" => 0, "message"=>"Data Not Found")));
       }
	}


}
// END video Class
/* End of file video.php */
/* Location: ./sytem/application/controlers/video.php */		
  