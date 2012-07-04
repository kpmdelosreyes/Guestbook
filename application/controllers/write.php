<?php
class Write extends CI_Controller
{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('guestbook');
        $this->load->helper(array('form', 'url'));
    }
    
    public function index() 
    {
        $this->load->view('guestbook/write');
    }
    
    public function save()
    {
       $data = $this->input->post();
       if(!empty($_FILES['file_upload']['name']))
        {
            /*file*/
            $file = $_FILES['file_upload'];
            $name = $file['name'];
            $sFileName = time()."_".$file['name'];
            $sPath = "./uploads/" . $sFileName;

            /*move uploaded file*/
            if (move_uploaded_file($file['tmp_name'], $sPath)) {
                    $sPath = "./uploads/" . $sFileName;
            }
        }
       $save = array(
           'writer' => $data['writer'],
           'subject' => $data['subject'],
           'message' => $data['message'],
           'filename' => $sFileName
       );
       $result = $this->guestbook->save($save);
       
       if ($result !== false) {
           redirect('/view/index/' . $result);
       }
    }
}