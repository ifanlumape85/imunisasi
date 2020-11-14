<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Dibuat oleh : ifan lumape
 * E-Mail : fnnight@gmail.com
 */
function seo_title($s) {
    $c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');

    $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
    
    //$s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
	//$seo = $s.'.html';
    //return $seo;
	return strtolower($s);
}
/* End of file seo_helper.php */
/* Location: ./application/helpers/seo_helper.php */