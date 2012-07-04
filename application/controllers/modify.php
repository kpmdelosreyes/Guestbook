<?php
class Modify extends CI_Controller
{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $id = $this->uri->segment(3);
        $data = array();
        $data = $this->guestbook->get_data($id);
        $data = array_shift($data);
        $this->load->view('guestbook/update', $data);
    }
    
    public function update()
    {
        $id = $this->uri->segment(3);
        $data = $this->input->post();
       
        $update = array(
            'writer' => $data['writer'],
            'subject' => $data['subject'],
            'message' => $data['message']
        );

        $result = $this->guestbook->update($update, $id);

        if ($result !== false) {
            redirect('/view/index/' . $id);
        }
    }
}
