<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//include Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Product extends REST_Controller {

    public function __construct() { 
        parent::__construct();
        
        //load user model
        $this->load->model('Product_Manage');
    }

    function product_get() {
        $products = $this->Product_Manage->get_products();
        $this->response($products, REST_Controller::HTTP_OK);
    }

    function pdf_post(){
        $pdf=$this->Product_Manage->send_quotation($this->post('name'),$this->post('address'),$this->post('email'),$this->post('products'),$this->post('multiplayingFactor'));
        $this->response($pdf, REST_Controller::HTTP_OK);
    }
} 