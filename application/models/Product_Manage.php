<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
define('FPDF_FONTPATH',APPPATH .'plugins/font/');
        require(APPPATH .'plugins/pdf.php');

class Product_Manage extends CI_Model {
    public function __construct() {
        parent::__construct();
        
        //load database library
        $this->load->database();
    }

    function get_products() {
        $query = $this->db->get('product');
        return $query->result_array();
    }

    function send_quotation($name,$address,$email,$products,$mf) {
        $this->create_PDF($name,$address,$email,$products,$mf);
    }

    function create_PDF($name,$address,$email,$products,$mf) {
        $header = array('Sr no', 'New Model No.', 'Item', 'Unit Price (Excluding GST)');
        $w = array(20, 50, 67, 53);
        $data=$this->get_products_from_ids($products);
        $rowhight=5;
        // Instanciation of inherited class
        $pdf = new PDF();
        //$pdf->SetMargins(10,0,10);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times','',12);
        $pdf->Ln(0);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'Ref:SEM/2018-19/PNCH-1', 0, 0, 'L' );       
        $pdf->Cell(0,$rowhight,date('d-m-Y'),0,1,'R');
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'To, ',0,1);
        $pdf->Cell(0,$rowhight,$name,0,1);
        //$pdf->Cell(0,$rowhight,'Petlas newborn and children hospital',0,1);
        $pdf->Cell(0,$rowhight,$address,0,1);
        //$pdf->Cell(0,$rowhight,'Raipur, Chhattisgarh',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'Sub: Your requirement of various models.',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'Dear Sir/Mam,',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'Thank you for your enquiry with and we are pleased to quote for your requirement as follow:',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        // Header
        $pdf->SetFont('Times','B',12);
        for($i=0;$i<count($header);$i++)
            $pdf->Cell($w[$i],7,$header[$i],1,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Times','',12);
        // 
        $sr_no=1;
        foreach($data as $row)
        {
            $pdf->Cell($w[0],$rowhight,$sr_no,'LR',0,'C');
            $pdf->Cell($w[1],$rowhight,$row['item_no'],'LR',0,'C');
            $pdf->Cell($w[2],$rowhight,$row['description'],'LR',0,'L');
            $pdf->Cell($w[3],$rowhight,$row['list_price']*$mf,'LR',0,'C');
            $sr_no++;
            $pdf->Ln();
        }
        // Closing line
        $pdf->Cell(array_sum($w),0,'',1,1,'T');
        $pdf->Cell(0,$rowhight,'',0,1,0);
        $pdf->SetFont('Times','BU',10);
        $pdf->Cell(0,$rowhight,'All our Manikins/Simulators are Original NASCO/Gaumard/3B Germany NATA/Limbs & Things UK/3-Dmed USAI Airsim','LRT',1,1);
        $pdf->Cell(0,$rowhight,'USA make, Manufactured in USA/Germany UK in respective plants under US/European Quality Control Standards and','LR',1,1);
        $pdf->Cell(0,$rowhight,'Procedures and packed in original US/European manufacturer`s carton with original manuals. WE DO NOT MARKET','LR',1,1);        
        $pdf->Cell(0,$rowhight,'ANY PRODUCTS SOURCED FROM CHINA','LR',1,1);        
        $pdf->Cell(0,$rowhight,'','LR',1,1);
        $pdf->Cell(0,$rowhight,'QUALITY AND ENVIRONMENTAL EMISSION STANDARD:','LR',1,1);
        $pdf->SetFont('Times','',10);
        $pdf->Cell(0,$rowhight,'Certified under DIN EN ISO 9001, and worlddidac Quality Charter and TUV NORD Umweltschutz Gmbh & Co.KG from time to','LR',1,1);
        $pdf->Cell(0,$rowhight,'time.','LBR',1,1);  
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'We await your valuable order.',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'Commercial T&C',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'1. GST Extra at 18%',0,1);
        $pdf->Cell(0,$rowhight,'2. Delivery Time 4-6 Weeks.',0,1);
        $pdf->Cell(0,$rowhight,'3. Includes free Chart on Do`s & Dont`s in Simulation.',0,1);
        $pdf->Cell(0,$rowhight,'4. HSN Code 9023.',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'For SEM Trainers & Systems',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'',0,1);
        $pdf->Cell(0,$rowhight,'Patel Sunita',0,1);
        $pdf->Cell(0,$rowhight,'Manager Admin',0,1);
        $pdf -> output ('pdf/quotation'.date('d-m-Y').'.pdf','F');     
        $this->send_email($email);
    }

    function get_products_from_ids($products){
        $query = $this->db->query('SELECT * FROM product where item_no in ('.$products.')');
        return $query->result_array();
    }

    function send_email($email) {
        // $config = Array(
        //     'protocol' => 'smtp',
        //     'smtp_host' => 'ssl://smtp.googlemail.com',
        //     'smtp_port' => 465,
        //     'smtp_user' => 'tapansmart98@gmail.com', 
        //     'smtp_pass' => 'Tp$123321', 
        //     'mailtype' => 'html',
        //     'charset' => 'iso-8859-1',
        //     'wordwrap' => TRUE
        // );    
        // $config = Array(
        //         'protocol' => 'smtp',
        //         'smtp_host' => 'mail.semtrainers.com',
        //         'smtp_port' => 465,
        //         'smtp_user' => 'sales@semtrainers.com', 
        //         'smtp_pass' => 'Wenglish2.1', 
        //         'mailtype' => 'html',
        //         'charset' => 'iso-8859-1',
        //         'wordwrap' => TRUE
        //     );    

        //     $this->load->library('email');

        // $this->email->set_newline("\r\n");

        // $config['protocol'] = 'smtp';
        // $config['smtp_host'] = 'mail.semtrainers.com';
        // $config['smtp_port'] = '465';
        // $config['smtp_user'] = 'sales@semtrainers.com';
        // $config['smtp_from_name'] = 'Shyam';
        // $config['smtp_pass'] = 'Wenglish2.1';
        // $config['wordwrap'] = TRUE;
        // $config['newline'] = "\r\n";
        // $config['mailtype'] = 'html';  
          
        // $body="Dear Sir/Madam,</br>With reference to your Enquiry for various Models/Manikins and Simulators. </br>Please find attached our quotation for the same, Should You need any clarification kindly get in touch with us on below mentioned details.</br></br>Thanks and regards,</br>Shyam";
        // // $this->load->library('email');
        // // $this->email->initialize($config);
        // $this->email->set_newline("\r\n");
        // $this->email->from('sales@semtrainers.com');
        // $this->email->to($email);
        // $this->email->subject('Quotation');
        // $this->email->message($body);
        // $this->email->attach('pdf/quotation'.date('d-m-Y').'.pdf');
        // if($this->email->send())
        // {
            
        // }
        // else
        // {
        //     show_error($this->email->print_debugger());
        // }
        error_reporting( E_ALL );
    $from = "sales@semtrainers.com";
    $to = "tapansmart98@gmail.com";
    $subject = "Checking PHP mail";
    $message = "PHP mail works just fine";
    $headers = "From:" . $from;
    mail($to,$subject,$message, $headers);
    echo "The email message was sent.";
    }
}

?>