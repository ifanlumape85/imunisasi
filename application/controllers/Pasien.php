<?php
class Pasien extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('Pasien_model', 'pasien', TRUE);
	}

	function index()
	{
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		} else {
			$this->load->helper('url');
			$data = array(
				'title' 	=> 'Data Pasien',
				'main_view' => 'pasien/pasien',
				'form_view' => 'pasien/pasien_form',
			);

			$this->load->view('admin/template', $data);
		}
	}

	function get_umur($tgl)
	{
		// tanggal lahir
		$tanggal = new DateTime($tgl);
		// tanggal hari ini
		$today = new DateTime('today');
		// tahun
		$y = $today->diff($tanggal)->y;
		// bulan
		$m = $today->diff($tanggal)->m;
		// hari
		$d = $today->diff($tanggal)->d;
		// echo "Umur: " . $y . " tahun " . $m . " bulan " . $d . " hari";
		return $m;
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->pasien->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pasien) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="' . $pasien->id_pasien . '">';
			$row[] = $no;
			$row[] = $pasien->nama_pasien;
			$row[] = $pasien->tempat_lahir . ' ' . tgl_indonesia2($pasien->tgl_lahir) . '<br />(' . $this->get_umur($pasien->tgl_lahir) . " bulan)";
			$row[] = $pasien->no_telp;
			if ($pasien->photo)
				$row[] = '<img src="' . base_url('upload/pasien/thumbs/' . $pasien->photo) . '"/>';
			else
				$row[] = '(No pasien)';

			$row[] = tgl_indonesia2($pasien->tgl_input);
			$row[] = tgl_indonesia2($pasien->tgl_update);

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary btn-flat" href="javascript:void(0)" title="Edit" onclick="edit_pasien(' . "'" . $pasien->id_pasien . "'" . ')"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Ubah</a>
				  <a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_pasien(' . "'" . $pasien->id_pasien . "'" . ')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" 				=> $_POST['draw'],
			"recordsTotal" 		=> $this->pasien->count_all(),
			"recordsFiltered" 	=> $this->pasien->count_filtered(),
			"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->pasien->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();

		$data = array(
			'nama_pasien' => $this->input->post('nama_pasien', TRUE),
			'nomor_pasien' => $this->input->post('nomor_pasien', TRUE),
			'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
			'tgl_lahir' => date('Y-m-d', strtotime($this->input->post('tgl_lahir', TRUE))),
			'no_telp' => $this->input->post('no_telp', TRUE),
			'tgl_imunisasi' => $this->input->post('tgl_imunisasi', TRUE),
			'aktif' => $this->input->post('aktif', TRUE),
			'tgl_input' => date('Y-m-d'),
			'tgl_update' => date('Y-m-d'),
		);

		if ($this->input->post('password', TRUE) != "") {
			$data['password'] = md5($this->input->post('password', TRUE));
		}

		if (!empty($_FILES['photo']['name'])) {
			$upload = $this->_do_upload();
			$data['photo'] = $upload;
		}
		$insert = $this->pasien->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();

		$data = array(
			'nama_pasien' => $this->input->post('nama_pasien', TRUE),
			'nomor_pasien' => $this->input->post('nomor_pasien', TRUE),
			'tempat_lahir' => $this->input->post('tempat_lahir', TRUE),
			'tgl_lahir' => date('Y-m-d', strtotime($this->input->post('tgl_lahir', TRUE))),
			'no_telp' => $this->input->post('no_telp', TRUE),
			'tgl_imunisasi' => $this->input->post('tgl_imunisasi', TRUE),
			'aktif' => $this->input->post('aktif', TRUE),
			'tgl_update' => date('Y-m-d'),
		);

		if ($this->input->post('password', TRUE) != "") {
			$data['password'] = md5($this->input->post('password', TRUE));
		}

		if ($this->input->post('remove_photo')) // if remove photo checked
		{
			if (file_exists('upload/pasien/' . $this->input->post('remove_photo')) && $this->input->post('remove_photo')) {
				@unlink('upload/pasien/' . $this->input->post('remove_photo'));
				@unlink('upload/pasien/thumbs/' . $this->input->post('remove_photo'));
			}
			$data['photo'] = '';
		}

		if (!empty($_FILES['photo']['name'])) {
			$upload = $this->_do_upload();

			//delete file
			$pasien = $this->pasien->get_by_id($this->input->post('id'));
			if (file_exists('upload/pasien/' . $pasien->photo) && $pasien->photo) {
				@unlink('upload/pasien/' . $pasien->photo);
				@unlink('upload/pasien/thumbs/' . $pasien->photo);
			}

			$data['photo'] = $upload;
		}
		$this->pasien->update(array('id_pasien' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$pasien = $this->pasien->get_by_id($id);
		if (file_exists('upload/pasien/' . $pasien->photo) && $pasien->photo) {
			@unlink('upload/pasien/' . $pasien->photo);
			@unlink('upload/pasien/thumbs/' . $pasien->photo);
		}
		$this->pasien->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->pasien->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}
	private function _do_upload()
	{
		$config['upload_path']    = 'upload/pasien/';
		$config['allowed_types']  = 'jpg|jpeg';
		$config['max_size']       = 10240; //set max size allowed in Kilobyte
		$config['max_width']      = 1000; // set max width image allowed
		$config['max_height']     = 1000; // set max height allowed
		$config['file_name']      = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('photo')) //upload and validate
		{
			$data['inputerror'][] = 'photo';
			$data['error_string'][] = 'Upload error: ' . $_FILES['photo']['type'] . ' ' . $this->upload->display_errors('', ''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		$file 		= $this->upload->data();
		$nama_file 	= $file['file_name'];

		$config = array(
			'source_image' 		=> $file['full_path'],
			'new_image' 		=> './upload/pasien/thumbs/',
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


		if ($this->input->post('nama_pasien') == '') {
			$data['inputerror'][] = 'nama_pasien';
			$data['error_string'][] = 'Nama Pasien is required';
			$data['status'] = FALSE;
		}

		if ($this->input->post('tempat_lahir') == '') {
			$data['inputerror'][] = 'tempat_lahir';
			$data['error_string'][] = 'Tempat Lahir is required';
			$data['status'] = FALSE;
		}

		if ($this->input->post('tgl_lahir') == '') {
			$data['inputerror'][] = 'tgl_lahir';
			$data['error_string'][] = 'Tgl Lahir is required';
			$data['status'] = FALSE;
		}

		if ($this->input->post('no_telp') == '') {
			$data['inputerror'][] = 'no_telp';
			$data['error_string'][] = 'No Telp is required';
			$data['status'] = FALSE;
		}


		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	function login_pasien()
	{
		$username = $this->input->post('username', TRUE);
		$password = $this->input->post('password', TRUE);

		$cek = $this->db->query("select * from pasien where no_telp='$username' and password='" . md5($password) . "'");
		if ($cek->num_rows() > 0) {
			$results = array();
			foreach ($cek->result() as $row) {
				array_push(
					$results,
					array(
						"id"         	=> $row->id_pasien,
						"nama_pasien"	=> $row->nama_pasien,
						"nomor_pasien"	=> $row->nomor_pasien,
						"tempat_lahir"	=> $row->tempat_lahir,
						"tgl_lahir"		=> $row->tgl_lahir,
						"no_telp"    	=> $row->no_telp,
						"photo"  		=> $row->photo,
						"password"  	=> $row->password,
						"token"    		=> $row->token,
						"aktif"    		=> $row->aktif,
						"tgl_input"  	=> tgl_indonesia2($row->tgl_input),
						"tgl_update" 	=> tgl_indonesia2($row->tgl_update)
					)
				);
			}
			print(json_encode(array("code" => 1, "message" => "Login Sukses", "pasiens" => $results)));
		} else {
			print(json_encode(array("code" => 0, "message" => "Login Gagal")));
		}
	}


	function list_pasien()
	{
		$id = $_POST['id'];
		$query = $_POST['query'];
		$limit = $_POST['limit'];
		$start = $_POST['start'];

		// $query='';
		// $limit=10;
		// $start=0;

		$sql = "SELECT * FROM pasien ";

		if ($query != '') {
			$sql .= " WHERE nama_pasien LIKE '%$query%' ";
		}

		$sql .= " ORDER BY pasien.tgl_input DESC LIMIT $limit OFFSET $start ";

		$qry = $this->db->query($sql);
		if ($qry->num_rows() > 0) {

			$results = array();
			foreach ($qry->result() as $row) {
				array_push(
					$results,
					array(
						"id"         	=> $row->id_pasien,
						"nama_pasien"	=> $row->nama_pasien,
						"nomor_pasien"	=> $row->nomor_pasien,
						"tempat_lahir"	=> $row->tempat_lahir,
						"tgl_lahir"		=> $row->tgl_lahir,
						"no_telp"    	=> $row->no_telp,
						"photo"  		=> $row->photo,
						"password"  	=> $row->password,
						"token"    		=> $row->token,
						"aktif"    		=> $row->aktif,
						"tgl_input"  	=> tgl_indonesia2($row->tgl_input),
						"tgl_update" 	=> tgl_indonesia2($row->tgl_update)
					)
				);
			}
			print(json_encode(array("code" => 1, "message" => "Success", "pasiens" => $results)));
		} else {
			print(json_encode(array("code" => 0, "message" => "Data Not Found")));
		}
	}
}
// END pasien Class
/* End of file pasien.php */
/* Location: ./sytem/application/controlers/pasien.php */
