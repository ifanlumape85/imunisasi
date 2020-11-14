<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * @author		Ifan Lumape
 * @Email		fnnight@gmail.com.
 * @Start		22 April 2014
 * @Web			http://www.ifanlumape.com
 *
 */
class Dashboard extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		
	}
	
	function index()
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->helper('url');
			$data = array(
				'title' 			=> 'Dashboard', 
				'main_view' 		=> 'admin/dashboard', 
				'form_view' 		=> 'admin/dashboard_form',
			);
			$this->load->view('admin/template', $data);
		}
		else
		{
			redirect(base_url());
		}
	}

	function instansi($id_instansi)
	{
		if ($this->session->userdata('login') == TRUE)
		{
			$this->load->helper('url');
			$data = array(
				'title' 		=> 'Dashboard', 
				'main_view' 	=> 'admin/dashboard', 
				'id_instansi'	=> encode($id_instansi),
				'form_view' 	=> 'admin/dashboard_form2',
			);
			$this->load->view('admin/template', $data);
		}
		else
		{
			redirect(base_url());
		}
	}
	
	function absensi()
	{
		$this->load->helper('url');
		$data = array(
			'title' 	=> 'Dashboard', 
			'main_view' => 'pegawai/dashboard', 
			'form_view' => 'pegawai/dashboard_form',
		);
		$this->load->view('admin/template', $data);
	}

	function kinerja()
	{
		$this->load->helper('url');
		$data = array(
			'title' 	=> 'Dashboard', 
			'main_view' => 'pegawai/dashboard', 
			'form_view' => 'pegawai/dashboard_form',
		);
		$this->load->view('admin/template', $data);
	}

	function profil()
	{
		$this->load->helper('url');
		$data = array(
			'title' 	=> 'Dashboard', 
			'main_view' => 'pegawai/dashboard', 
			'form_view' => 'pegawai/dashboard_form',
		);
		$this->load->view('admin/template', $data);
	}
}
// END Admin Class
/* End of file admin.php */
/* Location: ./sytem/application/controlers/admin.php */