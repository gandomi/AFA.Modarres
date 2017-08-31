<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <title>Pure CSS Login Form</title>
    <script src="http://s.codepen.io/assets/libs/modernizr.js" type="text/javascript"></script>


    
    
    
        <link rel="stylesheet" href="<?=base_url("assets/css/style.css")?>">

    
    
    
  </head>

  <body>

<div class="stand">
  <div class="outer-screen">
    <div class="inner-screen">
      <?php

        $result = $this->session->flashdata('result');
        if($result['success'] === false)
        {
          echo $result['message'];
          echo $result['errors'];
        }

      ?>
      <div class="form">
        <?php
          $attributes = array('id' => 'login_form');
          $this->load->helper('form');
          echo form_open('login/auth', $attributes);
          echo form_input(array('type'=>'text', 'name'=>'username', 'class'=>'zocial-dribbble', 'placeholder'=>'Username'));
          echo form_password(array('name'=>'password', 'placeholder'=>'Password'));
          echo form_checkbox(array('name'=>'remember', 'value'=>'1'));
          echo form_input(array('type'=>'submit', 'value'=>'Login'));
          echo form_close();
        ?>
        <a href="">Lost your password?</a>
      </div> 
    </div> 
  </div> 
</div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  </body>
</html>
