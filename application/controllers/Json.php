<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	
	
	function index()
	{
		header("Content-Type: application/json");
		$produk_hukums= $this->db->query("select produk_hukum.*, jenis_produk_hukum.nama_jenis_produk_hukum, jenis_produk_hukum.singkatan from produk_hukum left join jenis_produk_hukum on produk_hukum.id_jenis_produk_hukum=jenis_produk_hukum.id_jenis_produk_hukum"); //query sql yang disesuaikan
		$varjson = array();
$row_array = (object)array();
		if ($produk_hukums->num_rows > 0) 
		{

		    foreach($produk_hukums->result() as $row) 
		    {
		    	$sumber = '-';
		    	if($row->sumber!="")
		    		$sumber = $row->sumber;

		    	$subjek = '-';
		    	if ($row->subjek!="")
		    		$subjek = $row->subjek;

				$row_array->idData=$row->id_produk_hukum; //berisi id dokumen
				$row_array->tahun_pengundangan=$row->tahun; //berisi tahun penetapan atau tahun terbit ex. 2019
				$row_array->tanggal_pengundangan=$row->tanggal; //berisi tahun bulan tanggal (YYYY-MM-DD) ex. 2019-04-22
				$row_array->jenis=$row->nama_jenis_produk_hukum; //berisi jenis peraturan ex. Peraturan Daerah
				$row_array->noPeraturan=$row->nomor; //berisi no peraturan ex. 24
				$row_array->judul=$row->nama_produk_hukum; //berisi judul ex. Peraturan Pemerintah No 1 Tahun 2019 Tentang Peraturan
				$row_array->noPanggil='-'; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
				$row_array->singkatanJenis=$row->singkatan; //berisi singkatan dari jenis ex. PERMEN/KEPMEN
				$row_array->tempatTerbit='Lolak'; //berisi tempat terbit
				$row_array->penerbit='-'; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
				$row_array->deskripsiFisik='-'; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
				$row_array->sumber= $sumber; //berisi sumber dokumen hukum, contoh PUU bersumber dari Berita Negara Tahun .... Nomor .....
				$row_array->subjek=$subjek; //berisi kata kunci dari dokumen hukum
				$row_array->isbn='-'; //khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
				$row_array->status=$row->status; //berisi status PUU ex.berlaku/tidak berlaku
				$row_array->bahasa='Bahasa Indonesia'; //berisi bahasa dari dokumen hukum tersebut
				$row_array->bidangHukum='-'; //berisi pembidangan/pengkategorian isi dokumen hukum(opsional menyesuaikan kebutuhan instansi)
				$row_array->teuBadan='Pemerintah Kabupaten Bolaang Mongondow';//nama instansi terkait
				$row_array->nomorIndukBuku='-';//khusus untuk monografi/buku bila PUU bisa dikosongkan atau diisi '-'
				$row_array->fileDownload=$row->photo; //berisi nama file ex. peraturan.pdf, peraturan.docx
				$row_array->urlDownload='http://jdih.bolmongkab.go.id/jdih/produk_hukum/download/'.$row->photo; //berisi url dan nama file ex. domain.com/peraturan.pdf atau menyesuaikan
				$row_array->urlDetailPeraturan=base_url('produk_hukum/detail/'.$row->id_produk_hukum); //berisi url halaman detail peraturan
				$row_array->operasi="4"; //wajib ada
				$row_array->display="1"; //wajib ada
		      	array_push($varjson,json_decode(json_encode($row_array)));
		    }

		    echo json_encode($varjson);
		} 
		else 
		{
		    echo "0 results";
		}
		
	}
}
// END Admin Class
/* End of file apo.php */
/* Location: ./sytem/application/controlers/api.php */
