<?php
class lists extends CI_Controller
{
    public function __construct() {
        parent::__construct();        
        $this->load->library('class_Pagination');
    }
    
    public function index()
    {
        $iRows = 10;
        $iPage = $_GET['page'] ? $_GET['page'] : 1;
        $aOption['limit'] = $iRows;
        $aOption['offset'] = $iRows * ($iPage - 1);
        
        $data = array();
        $data['data'] = $this->guestbook->get_data(null,$aOption);
        $iResultCount = $this->guestbook->getResultCount();
        
        $this->class_pagination->set($iPage);
      
        $data['paginate'] = $this->class_pagination->paginate($iRows, $iResultCount);
        
       
        $this->load->view('guestbook/list', $data);
    }
    
    public function delete()
    {
        $param = $this->input->post('post');
        $param = explode("-", substr($param, 0, strlen($param)-1));
        $result = $this->guestbook->delete($param);
        
        if ($result === true) {
            echo "1";
        }
    }
    
    public function search()
    {
        $param = $this->input->post();
        
        $data['data'] = $this->guestbook->get_data_search($param);
        
        $this->load->view('guestbook/reload_list', $data);
    }
    
    public function reload_list()
    {
        $data = array();
        $data['data'] = $this->guestbook->get_data();
        $this->load->view('guestbook/list', $data);
    }
}
