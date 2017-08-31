<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends CI_Controller {


    function __construct()
    {
        parent::__construct();
        $this->load->helper('download');
        $this->load->helper('file');
    }

    public function index()
    {
//        $this->load->model('Courses_model');
//        $temp = $this->Courses_model->getCourses()->result();
//        echo '<pre>';
//        echo print_r($temp);
//        die();
        redirect("home");
    }

    public function entry($dir, $filename)
    {
        $filename = rawurldecode($filename); // remove space and % from uri
        $directory = DLPATH.$dir;
        $path = $directory."/".$filename;

        if(!empty($filename) && !empty($dir) && is_dir($directory) && get_file_info($path)){

            // download counter
            $this->load->model('Files_model');
            $this->Files_model->downloadCounter(substr($dir, 6), $filename);

            redirect(base_url("dl/$dir/$filename"));
        }else{
            show_404();
        }
    }
}
