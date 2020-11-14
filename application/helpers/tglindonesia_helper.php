<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Dibuat oleh : ifan lumape
 * E-Mail : fnnight@gmail.com
 */
function tgl_indo2($tgl){
		$tanggal = substr($tgl,8,2);
		$bulan = substr($tgl,5,2);
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun;		 
}
function tgl_indo($tgl){
		$tanggal = substr($tgl,8,2);
		$bulan = getBulan(substr($tgl,5,2));
		$tahun = substr($tgl,0,4);
		return $tanggal.' '.$bulan.' '.$tahun;		 
}
function getBulan($bln){
	switch ($bln){
		case 1: 
			return "Januari";
			break;
		case 2:
			return "Februari";
			break;
		case 3:
			return "Maret";
			break;
		case 4:
			return "April";
			break;
		case 5:
			return "Mei";
			break;
		case 6:
			return "Juni";
			break;
		case 7:
			return "Juli";
			break;
		case 8:
			return "Agustus";
			break;
		case 9:
			return "September";
			break;
		case 10:
			return "Oktober";
			break;
		case 11:
			return "November";
			break;
		case 12:
			return "Desember";
			break;
	}
} 	
function tgl_indonesia($tanggal)
{
	date_default_timezone_set('Asia/Makassar');
	$hari_array = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
	
	$hr 	= date('w', strtotime($tanggal));
	$hari 	= $hari_array[$hr];
	$tgl 	= date('d-m-Y', strtotime($tanggal));
	
	$hr_tgl = "$hari, $tgl";
	return $hr_tgl;
}

function tgl_indonesia2($tanggal)
{
	if ($tanggal != '' && $tanggal != '1970-01-01'){
	date_default_timezone_set('Asia/Makassar');
	$bulan_array 	= array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	$hari_array 	= array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');	
	$hr 	= date('w', strtotime($tanggal));
	$hari 	= $hari_array[$hr];
	$bl 	= (int) date('m', strtotime($tanggal));
	$tgl 	= date('d', strtotime($tanggal));
	$thn 	= date('Y', strtotime($tanggal));
	$bulan 	= $bulan_array[$bl];
	$hr_tgl = "$tgl $bulan $thn";
	}
	else{
		$hr_tgl = '';
	}	
	return $hr_tgl;
}

function bulan_romawi($bln)
{
	$romawi_array = array('', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII');
	$bln_romawi = $romawi_array[$bln];
	return $bln_romawi;		
}

function hari_ini($hari)
{
	$hari_array 	= array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
	@$hri = $hari_array[$hari];
	return $hri;
}

function tgl_terbilang($tgl)
{
	$tgl_array = array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas', 'Dua belas', 'Tiga belas', 'Empat belas', 'Lima belas', 'Enam belas', 'Tujuh belas', 'Delapan belas', 'Sembilan belas', 'Dua puluh', 'Dua puluh satu', 'Dua puluh dua', 'Dua puluh tiga', 'Dua puluh empat', 'Dua puluh lima', 'Dua puluh enam', 'Dua puluh tujuh', 'Dua puluh delapan', 'Dua puluh sembilan', 'Tiga puluh', 'Tiga puluh satu');	
	return $tgl_array[$tgl];
}

function bln_terbilang($bln)
{
	$bln = (int) $bln;
	$bulan_array 	= array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
	return $bulan_array[$bln];	
}

function tahun_terbilang($thn)
{
	$words = "";
	$arr_number = array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas');
	if ($thn < 12)
	{
		$words = "".$arr_number[$thn];
	}
	elseif ($thn < 20)
	{
		$words = tahun_terbilang($thn-10).' belas';
	}
	elseif ($thn < 100)
	{
		$words = tahun_terbilang($thn/10).' puluh '.tahun_terbilang($thn%10);
	}
	elseif($thn < 200)
	{
		$words = 'seratus '.tahun_terbilang($thn-100);	
	}
	elseif($thn < 1000)
	{
		$words = tahun_terbilang($thn/100).' ratus '.tahun_terbilang($thn%100);	
	}
	elseif($thn < 2000)
	{
		$words = 'seribu '.tahun_terbilang($thn-1000);	
	}
	elseif($thn < 1000000)
	{
		$words = tahun_terbilang($thn/1000).' ribu '.tahun_terbilang($thn%1000);	
	}
	elseif($thn < 1000000)
	{
		$words = tahun_terbilang($thn/1000000).' juta '.tahun_terbilang($thn%1000000);
	}
	else
	{
		$words = 'undefined';	
	}
	return $words;
}
/* End of file tglindonesia_helper.php */
/* Location: ./application/helpers/tglindonesia_helper.php */