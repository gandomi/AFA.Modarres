<?php
/**
 * Login Model
 */

class Login_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function check()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);
        $remember = $this->input->post('remember', true);


        $admin = $this->db->get_where('admin', array('username'=>"$username", 'password'=>$this->encrypt->hash_sha256($password._SALT_)));
        $cntr = $admin->num_rows();

        if($cntr > 0)
        {
            $admin_id = $admin->row(0)->id;

            $session_data = array(
                'admin_id'        => $admin_id,
                'logged_in' => TRUE
            );
            $this->session->set_userdata($session_data);

            if($remember == 1)
            {
                $login_text = $admin_id.'_GandoM_TRUE';
                $this->load->library('encrypt');
                $cookie_value = $this->encrypt->encode($login_text, ENCRYPT_KEY);

                $data_cookie = array(
                    'name'=>'Cookie',
                    'value'=>$cookie_value,
                    'expire'=>60*60*24*365
                );
                $this->input->set_cookie($data_cookie);
            }

            return true;
        }
        else
        {
            return false;
        }
    }
}