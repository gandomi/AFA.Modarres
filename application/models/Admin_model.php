<?php
/**
 * Login Model
 */

class Admin_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function getAdminName()
    {
        $this->db->select('name')->where('id', 1);
        return $this->db->get('admin')->row_array();
    }
}