<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pdf extends \setasign\Fpdi\Fpdi
{
     // Page footer
	function Footer()
	{
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    //Gunakan aplikasi veryDS untuk mengetahui keaslian dokumen ini.
	    // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	    $this->Cell(0,10,'Dokumen ini di tanda tangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh BSrE.',0,0,'C');
	}
}