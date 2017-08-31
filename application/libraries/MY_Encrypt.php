<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Encrypt extends CI_Encrypt {

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function hash_sha256($string)
    {
        return hash('sha256', $string);
    }
}