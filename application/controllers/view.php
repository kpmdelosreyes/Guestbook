<?php
class View extends CI_Controller
{
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('guestbook');
    }
    
    public function index()
    {
        $id = $this->uri->segment(3);
        $data = array();
        $data = $this->guestbook->get_data($id, null);
        $data = array_shift($data);
        $this->load->view('guestbook/view', $data);
    }
}
