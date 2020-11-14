<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('Berita_model', 'berita', TRUE);
		$this->load->model('Produk_hukum_model', 'produk_hukum', TRUE);
		$this->load->model('Perkara_hukum_model', 'perkara_hukum', TRUE);
		$this->load->model('Jenis_produk_hukum_model', 'jenis_produk_hukum', TRUE);
		$this->load->model('Jenis_perkara_model', 'jenis_perkara', TRUE);
	}


	public function index()
	{
		$jenis_produk_hukums = $this->jenis_produk_hukum->get_list_jenis_produk_hukum();		
		$opt_jenis_produk_hukum = array('' => '-- Jenis Produk Hukum --');
		foreach ($jenis_produk_hukums as $i => $v) {
	        $opt_jenis_produk_hukum[$i] = $v;
	    }

	    $jenis_perkaras = $this->jenis_perkara->get_list_jenis_perkara();		
		$opt_jenis_perkara = array('' => '-- Jenis Perkara --');
		foreach ($jenis_perkaras as $i => $v) {
	        $opt_jenis_perkara[$i] = $v;
	    }

		$data = array(
		'opt_jenis_produk_hukum' => $opt_jenis_produk_hukum,
		'jenis_produk_hukums' => $jenis_produk_hukums,
		'opt_jenis_perkara' => $opt_jenis_perkara,
		'jenis_perkaras' => $jenis_perkaras,
		'title' => 'Jaringan Data dan Informasi Hukum (JDIH) Bolaang Mongondow', 
		'main_view' => 'welcome',
		);

		$this->load->view('template', $data);
	}
}
