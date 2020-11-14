<?php
class Reminder extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('reminder_model', 'reminder', TRUE);
	}

	function index()
	{
		if ($this->session->userdata('login') != TRUE) {
			redirect('login');
		} else {
			$this->load->helper('url');
			$data = array(
				'title' 	=> 'Data Reminder',
				'main_view' => 'reminder/reminder',
				'form_view' => 'reminder/reminder_form',
			);

			$this->load->view('admin/template', $data);
		}
	}

	public function ajax_list()
	{
		$this->load->helper('url');
		$list = $this->reminder->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $reminder) {
			$no++;
			$row = array();

			$row[] = '<input type="checkbox" class="data-check" value="' . $reminder->id_reminder . '">';
			$row[] = $no;
			$row[] = $reminder->nama_pasien . '<br />' . $reminder->judul_reminder;
			$row[] = $reminder->isi_reminder;
			$row[] = tgl_indonesia2($reminder->tgl_reminder);

			//add html for action
			$row[] = '<a class="btn btn-sm btn-danger btn-flat" href="javascript:void(0)" title="Hapus" onclick="delete_reminder(' . "'" . $reminder->id_reminder . "'" . ')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Hapus</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" 				=> $_POST['draw'],
			"recordsTotal" 		=> $this->reminder->count_all(),
			"recordsFiltered" 	=> $this->reminder->count_filtered(),
			"data" 				=> $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_delete($id)
	{
		$reminder = $this->reminder->get_by_id($id);
		$this->reminder->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_bulk_delete()
	{
		$list_id = $this->input->post('id');
		foreach ($list_id as $id) {
			$this->reminder->delete_by_id($id);
		}
		echo json_encode(array("status" => TRUE));
	}

	function cron_job()
	{
		$vaksin = [
			0 => "BCG, Hepatitis BO 1, Polio 0",
			1 => "BCG",
			2 => "BCG, Polio 1, DPT-HB-HIB 1",
			4 => "Polio 2, DPT-HB-HIB 2",
			6 => "Polio 3 (IPV), DPT-HB-HIB 3",
			9 => "Campak",
		];
		$pasiens = $this->db->query("select * from pasien");
		if ($pasiens->num_rows() > 0) {
			foreach ($pasiens->result() as $pasien) {
				$umur = $this->get_umur($pasien->tgl_lahir);
				if (array_key_exists($umur, $vaksin)) {
					$rest_vaksin = $vaksin[$umur];
					$cek = $this->db->query("select * from reminder where id_pasien='$pasien->id_pasien' and tgl_reminder='" . date('Y-m-d') . "'");
					if ($cek->num_rows() < 1) {
						$this->db->query("insert into reminder set
						id_pasien='$pasien->id_pasien',
						judul_reminder='" . tgl_indonesia2(date('Y-m-d')) . " | Vaksin " . $rest_vaksin . "',
						isi_reminder='Hallo bunda, Jangan lupa di umur " . $umur . " bulan dede bayi harus diberi vaksin " . $rest_vaksin . "',
						tgl_reminder='" . date('Y-m-d') . "',
						dibaca='N'");
					}
				}
			}
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

	function list_reminder()
	{
		$id = $_POST['id'];
		$query = $_POST['query'];
		$limit = $_POST['limit'];
		$start = $_POST['start'];

		//       $id=1;//$_POST['id'];
		//     	$query='';//$_POST['query'];
		// $limit=10;//$_POST['limit'];
		//       $start=0;//$_POST['start'];

		$sql = "SELECT * FROM reminder WHERE id_pasien='$id'";

		if ($query != '') {
			$sql .= " AND judul_reminder LIKE '%$query%' ";
		}

		$sql .= " ORDER BY tgl_reminder DESC LIMIT $limit OFFSET $start ";

		$qry = $this->db->query($sql);
		if ($qry->num_rows() > 0) {

			$results = array();
			foreach ($qry->result() as $row) {
				array_push(
					$results,
					array(
						"id"         => $row->id_reminder,
						"id_pasien"         => $row->id_pasien,
						"judul_reminder" => $row->judul_reminder,
						"isi_reminder"  		 => $row->isi_reminder,
						"tgl_reminder"  => $row->tgl_reminder,
						"dibaca"    => $row->dibaca
					)
				);
			}
			print(json_encode(array("code" => 1, "message" => "Success", "reminders" => $results)));
		} else {
			print(json_encode(array("code" => 0, "message" => "Data Not Found")));
		}
	}
}
// END reminder Class
/* End of file reminder.php */
/* Location: ./sytem/application/controlers/reminder.php */
