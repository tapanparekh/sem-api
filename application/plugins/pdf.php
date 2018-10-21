<?Php
require('fpdf.php');

class PDF extends FPDF
{
// Page header
    function Header()
    {
        $this->Cell(0,6,'',0,1);
        $this->Cell(0,6,'',0,1);
        $this->Cell(0,6,'',0,1);
        $this->Cell(0,6,'',0,1);
        $this->Image('images/header.jpg',0,0,-130);
    }
    // Page footer
    function Footer()
    {
        $this->SetY(-30);
        $this->Image('images/footer.jpg');
        $this->Cell(0,6,'',0,1);
        $this->Cell(0,6,'',0,1);
        $this->Cell(0,6,'',0,1);
    }
}
?>