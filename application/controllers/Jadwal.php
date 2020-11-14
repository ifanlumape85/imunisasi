<?php
class Jadwal extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Jadwal_model', 'jadwal', TRUE);
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
			'title' 	=> 'Data Jadwal', 
			'main_view' => 'jadwal/jadwal', 
			'form_view' => 'jadwal/jadwal_form',
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
		$list = $this->jadwal->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $jadwal) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="'.$jadwal->id_jadwal.'">';
			$row[] = $no;
			$row[] = $jadwal->user_full_name; 
			$row[] = $jadwal->judul_jadwal; 
			 if($jadwal->pdf)
			 	$row[] = '<a href="'.base_url('upload/jadwal/'.$jadwal->pdf).'" target="_blank">'.$jadwal->pdf.'</a>';
			 else
			 	$row[] = '(No jadwal)';
			
			$row[] = tgl_indonesia2($jadwal->tgl_input); 
			$row[] = tgl_indonesia2($jadwal->tgl_update); 
						
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="thumbnail_jadwal('."'".$jadwal->id_jadwal."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Thumbnail</a> <a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_jadwal('."'".$jadwal->id_jadwal."'".')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_jadwal('."'".$jadwal->id_jadwal."'".')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';
		
			$data[] = $row;
		}

		$output = array(
		"draw" 				=> $_POST['draw'],
		"recordsTotal" 		=> $this->jadwal->count_all(),
		"recordsFiltered" 	=> $this->jadwal->count_filtered(),
		"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->jadwal->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
	
		
		$data = array(
		'id_user'=> $this->session->userdata('user_id'),
		'judul_jadwal'=> $this->input->post('judul_jadwal', TRUE),
		'deskripsi'=> $this->input->post('deskripsi', TRUE),
		'tgl_input'=> date('Y-m-d'),
		'tgl_update'=> date('Y-m-d'),
		);		
		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
		 	$data['pdf'] = $upload;
		}
		$insert = $this->jadwal->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		
		$data = array(
		'id_user'=> $this->session->userdata('user_id'),
		'judul_jadwal'=> $this->input->post('judul_jadwal', TRUE),
		'deskripsi'=> $this->input->post('deskripsi', TRUE),
		'tgl_update'=> date('Y-m-d'),
		);		
		
		if($this->input->post('remove_photo')) // if remove photo checked
		{
		 	if(file_exists('upload/jadwal/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
		 	{
				@unlink('upload/jadwal/'.$this->input->post('remove_photo'));
		 		@unlink('upload/jadwal/thumbs/'.$this->input->post('remove_photo'));
		 	}
		 	$data['pdf'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
		 	$upload = $this->_do_upload();
			
		 	//delete file
		 	$jadwal = $this->jadwal->get_by_id($this->input->post('id'));
		 	if(file_exists('upload/jadwal/'.$jadwal->pdf) && $jadwal->pdf)
		 	{
		 		@unlink('upload/jadwal/'.$jadwal->pdf);
		 		@unlink('upload/jadwal/thumbs/'.$jadwal->pdf);
		 	}

		 	$data['pdf'] = $upload;
		}
		$this->jadwal->update(array('id_jadwal' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{	
		$jadwal = $this->jadwal->get_by_id($id);
		if(file_exists('upload/jadwal/'.$jadwal->pdf) && $jadwal->pdf)
		{
		 	@unlink('upload/jadwal/'.$jadwal->pdf);
		 	@unlink('upload/jadwal/thumbs/'.$jadwal->pdf);
		}
		$this->jadwal->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->jadwal->delete_by_id($id);
        }
        echo json_encode(array("status" => TRUE));
    }	
	private function _do_upload()
	{
	 	$config['upload_path']    = 'upload/jadwal/';
        $config['allowed_types']  = 'pdf';
        $config['max_size']       = 10240; //set max size allowed in Kilobyte
        // $config['max_width']      = 1000; // set max width image allowed
        // $config['max_height']     = 1000; // set max height allowed
        $config['file_name']      = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

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

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		

		if ($this->input->post('judul_jadwal')=='')
		{
			$data['inputerror'][] = 'judul_jadwal';
			$data['error_string'][] = 'judul_jadwal is required';
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
		// $dokumens = $this->db->query("SELECT * FROM jadwal WHERE photo='".encode($id)."'");

		if (file_exists('upload/jadwal/'.$id))
		{
			$file_pdf 		= $id;
			$landscape 		= FALSE;
			$potrait 		= FALSE;
			$pdf 			= new Pdf();
			// add a page
			// get the page count
			$pageCount = $pdf->setSourceFile('upload/jadwal/'.$file_pdf);
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

	function list_jadwal()
	{
      	$query=$_POST['query'];
		$limit=$_POST['limit'];
        $start=$_POST['start'];

        // $query='';
        // $limit=10;
        // $start=0;

		$sql="SELECT jadwal.*, user.user_full_name, user.user_photo FROM jadwal LEFT JOIN user ON jadwal.id_user=user.user_id ";

        if ($query!='')
        {
            $sql .= " WHERE jadwal.judul_jadwal LIKE '%$query%' ";
        }

        $sql .= " ORDER BY jadwal.tgl_input DESC LIMIT $limit OFFSET $start ";
       
       $qry = $this->db->query($sql);
       if ($qry->num_rows() > 0)
       {

    		$results=array();
            foreach($qry->result() as $row)
            {
                array_push($results, 
                    array(
                    "id"         => $row->id_jadwal,
                    "judul_jadwal"=> $row->judul_jadwal,
                    "pdf"  		 => $row->pdf,
                    "deskripsi"  => $row->deskripsi,
                    "id_user"    => $row->user_photo,
                    "tgl_input"  => tgl_indonesia2($row->tgl_input),
                    "tgl_update" => tgl_indonesia2($row->tgl_update)
                    )
                );
            }
            print(json_encode(array("code" => 1, "message"=>"Success", "jadwals"=>$results)));
       }
       else
       {
       	print(json_encode(array("code" => 0, "message"=>"Data Not Found")));
       }
	}
}
// END jadwal Class
/* End of file jadwal.php */
/* Location: ./sytem/application/controlers/jadwal.php */		
  