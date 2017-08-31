<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Login Controller
 */

class Login extends CI_Controller
{

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $islogin = $this->input->cookie('GandomCookie');
        if(!empty($islogin))
        {
            $this->load->library('encrypt');
            $login_text = $this->encrypt->decode($islogin, ENCRYPT_KEY);
            $login_info = explode('_', $login_text);
            $this->admin_id = $login_info[0];
            $this->logged_in = $login_info[2];
            if($this->logged_in == TRUE && $login_info[1] == "GandoM")
            {
                redirect('admin');
            }
        }
        else
        {
            $logged_in = $this->session->userdata('logged_in');
            if(!empty($logged_in))
            {
                if($logged_in == TRUE)
                {
                    redirect('admin');
                }
            }

            $this->admin_id = $this->session->userdata('admin_id');
        }
    }

    function index()
    {
        $this->load->view('admin/login');
    }

    function auth()
    {
        $this->load->library('encrypt');
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', $this->lang->line('username'), _SAFE_INPUT_.'|required');
        $this->form_validation->set_rules('password', $this->lang->line('password'), _SAFE_INPUT_.'|required');

        if($this->form_validation->run() == FALSE)
        {
            $data['success'] = false;
            $data['message'] = $this->lang->line('login_fail');
            $data['errors'] = validation_errors();

            if($this->input->is_ajax_request()){
                echo json_encode($data);
            }else{
                $this->session->set_flashdata('result', $data);
                redirect('login');
            }
        }
        else
        {
            $this->load->model('Login_model');

            if($this->Login_model->check()){
                $data['success'] = true;
                $data['message'] = $this->lang->line('login_success');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    redirect('admin');
                }
            }else{
                $data['success'] = false;
                $data['message'] = $this->lang->line('admin_not_found');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('login');
                }
            }
        }
    }
}