<?php
/**
 * My Controller
 */

class MY_Controller extends CI_Controller
{
    public $admin_id, $logged_in;

    function __construct($config = array())
    {
        parent::__construct($config);

        $islogin = $this->input->cookie('GandomCookie');
        if(!empty($islogin))
        {
            $this->load->library('encrypt');
            $login_text = $this->encrypt->decode($islogin, ENCRYPT_KEY);
            $login_info = explode('_', $login_text);
            $this->admin_id = $login_info[0];
            $this->logged_in = $login_info[2];
            if($this->logged_in != TRUE || $login_info[1] != "GandoM")
            {
                redirect('login');
            }
        }
        else
        {
            $logged_in = $this->session->userdata('logged_in');
            if(!empty($logged_in))
            {
                if($logged_in != true)
                {
                    redirect('login');
                }
            }
            else
            {
                redirect('login');
            }
            $this->admin_id = $this->session->userdata('admin_id');
        }
    }
}