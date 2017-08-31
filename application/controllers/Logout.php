<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Login Controller
 */

class Logout extends CI_Controller
{
    function index()
    {
        $this->session->sess_destroy();
        $data_cookie = array(
            'name'=>'Cookie',
            'value'=>'',
            'expire'=>''
        ); // empty expire remove the cookie
        $this->input->set_cookie($data_cookie);

        redirect('home');
    }
}