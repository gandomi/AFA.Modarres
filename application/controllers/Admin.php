<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function index()
    {
        $this->load->model('Courses_model');
        $this->load->model('Files_model');
        $this->load->model('Admin_model');

        $courses = $this->Courses_model->getCourses(false);
        for($i = 0; $i < count($courses); $i++){
            $courses[$i]['files'] = $this->Files_model->getCoursesFiles($courses[$i]['id'], false);
            $timestamp = explode(' ', $courses[$i]['created_at']);
            $date = explode('-', $timestamp[0]);
            $courses[$i]['created_at'] = $this->jdf->gregorian_to_jalali($date[0], $date[1], $date[2], ' - ').'&nbsp;&nbsp;&nbsp;&nbsp;'.$timestamp[1];
        }
        $name = $this->Admin_model->getAdminName();

        $data['all'] = $courses;
        $data['adminName'] = $name['name'];
        $data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->view('admin/admin', $data);
    }

    public function addCourse()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', $this->lang->line('name'), _SAFE_INPUT_.'|required|max_length[255]');

        if($this->form_validation->run() == false)
        {
            $data['success'] = false;
            $data['message'] = $this->lang->line('form_fail');
            $data['errors'] = validation_errors();

            if($this->input->is_ajax_request()){
                echo json_encode($data);
            }else{
                $this->session->set_flashdata('result', $data);
                redirect('admin');
            }
        }
        else
        {
            $this->load->model('Courses_model');

            if($this->Courses_model->addCourse()){

                mkdir(DLPATH.'course'.$this->db->insert_id());

                $data['success'] = true;
                $data['message'] = $this->lang->line('add_course_success');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }else{
                $data['success'] = false;
                $data['message'] = $this->lang->line('add_course_fail');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }
        }
    }

    public function addFile()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('course_id', $this->lang->line('course_id'), _SAFE_INPUT_.'|required|is_natural_no_zero');

        if($this->form_validation->run() == false)
        {
            $data['success'] = false;
            $data['message'] = $this->lang->line('form_fail');
            $data['errors'] = validation_errors();

            if($this->input->is_ajax_request()){
                echo json_encode($data);
            }else{
                $this->session->set_flashdata('result', $data);
                redirect('admin');
            }
        }
        else
        {
            $config['upload_path'] = DLPATH.'course'.$this->input->post('course_id', true);
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->load->model('Files_model');
            $this->load->helper('file');

            $files        = $_FILES;
            $file_count    = count($_FILES['files']['name']);
            $error = array();

            for($i = 0; $i < $file_count; $i++)
            {
                $_FILES['files']['name']        = $files['files']['name'][$i];
                $_FILES['files']['type']        = $files['files']['type'][$i];
                $_FILES['files']['tmp_name']    = $files['files']['tmp_name'][$i];
                $_FILES['files']['error']       = $files['files']['error'][$i];
                $_FILES['files']['size']        = $files['files']['size'][$i];

                if( ! $this->upload->do_upload('files'))
                {
                    $error[$i]['file'] = $_FILES['files'];
                    $error[$i]['error'] = $this->upload->display_errors();
                    break;
                }
                else
                {
                    $data = $this->upload->data();

                    if(!$this->Files_model->addFile($data['file_name'], $_FILES['files']['size'])){
                        $error[$i]['file'] = $_FILES['files'];
                        $error[$i]['error'] = $this->lang->line('add_file_fail');
                    }
                }
            }

            $data = array();

            if(empty($error)){
                $data['success'] = true;
                $data['message'] = $this->lang->line('add_file_success');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }
            else{
                $data['success'] = false;
                $data['message'] = $this->lang->line('add_file_fail');
                $data['errors'] = $error;

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }
        }
    }

    public function editCourse()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', $this->lang->line('name'), _SAFE_INPUT_.'|required|max_length[255]');
        $this->form_validation->set_rules('course_id', $this->lang->line('course_id'), _SAFE_INPUT_.'|required|is_natural_no_zero');

        if($this->form_validation->run() == false)
        {
            $data['success'] = false;
            $data['message'] = $this->lang->line('edit_fail');
            $data['errors'] = validation_errors();

            if($this->input->is_ajax_request()){
                echo json_encode($data);
            }else{
                $this->session->set_flashdata('result', $data);
                redirect('admin');
            }
        }
        else
        {
            $this->load->model('Courses_model');

            if($this->Courses_model->editCourse()){

                $data['success'] = true;
                $data['message'] = $this->lang->line('edit_course_success');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }else{
                $data['success'] = false;
                $data['message'] = $this->lang->line('edit_course_fail');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }
        }
    }

    public function editFile()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', $this->lang->line('name'), _SAFE_INPUT_.'|required|max_length[255]');
        $this->form_validation->set_rules('course_id', $this->lang->line('course_id'), _SAFE_INPUT_.'|required|is_natural_no_zero');
        $this->form_validation->set_rules('file_id', $this->lang->line('file_id'), _SAFE_INPUT_.'|required|is_natural_no_zero');
        $this->form_validation->set_rules('download', $this->lang->line('download'), _SAFE_INPUT_.'|required|is_natural');

        if($this->form_validation->run() == false)
        {
            $data['success'] = false;
            $data['message'] = $this->lang->line('edit_fail');
            $data['errors'] = validation_errors();

            if($this->input->is_ajax_request()){
                echo json_encode($data);
            }else{
                $this->session->set_flashdata('result', $data);
                redirect('admin');
            }
        }
        else
        {
            $this->load->model('Files_model');

            if($this->Files_model->editFile()){

                $data['success'] = true;
                $data['message'] = $this->lang->line('edit_file_success');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }else{
                $data['success'] = false;
                $data['message'] = $this->lang->line('edit_file_fail');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }
        }
    }

    public function changeActive()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('type', $this->lang->line('type'), _SAFE_INPUT_.'|required|in_list[course,file]');

        $type = $this->input->post('type');
        if($type == 'course'){
            $this->form_validation->set_rules('course_id', $this->lang->line('course_id'), _SAFE_INPUT_.'|required|is_natural_no_zero');
        }elseif($type == 'file'){
            $this->form_validation->set_rules('file_id', $this->lang->line('file_id'), _SAFE_INPUT_.'|required|is_natural_no_zero');
        }

        if($this->form_validation->run() == false)
        {
            $data['success'] = false;
            $data['message'] = $this->lang->line('form_fail');
            $data['errors'] = validation_errors();

            if($this->input->is_ajax_request()){
                echo json_encode($data);
            }else{
                $this->session->set_flashdata('result', $data);
                redirect('admin');
            }
        }
        else
        {
            if($type == 'course'){
                $this->load->model('Courses_model');
                if($this->Courses_model->changeActive()){
                    $flag = TRUE;
                }else{
                    $flag = FALSE;
                }
            }elseif($type == 'file'){
                $this->load->model('Files_model');
                if($this->Files_model->changeActive()){
                    $flag = TRUE;
                }else{
                    $flag = FALSE;
                }
            }

            if($flag){

                $data['success'] = true;
                $data['message'] = $this->lang->line('change_active_success');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }else{
                $data['success'] = false;
                $data['message'] = $this->lang->line('change_active_fail');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }
        }
    }

    public function delete()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('type', $this->lang->line('type'), _SAFE_INPUT_.'|required|in_list[course,file]');

        $type = $this->input->post('type');
        if($type == 'course'){
            $this->form_validation->set_rules('course_id', $this->lang->line('course_id'), _SAFE_INPUT_.'|required|is_natural_no_zero');
        }elseif($type == 'file'){
            $this->form_validation->set_rules('file_id', $this->lang->line('file_id'), _SAFE_INPUT_.'|required|is_natural_no_zero');
        }

        if($this->form_validation->run() == false)
        {
            $data['success'] = false;
            $data['message'] = $this->lang->line('form_fail');
            $data['errors'] = validation_errors();

            if($this->input->is_ajax_request()){
                echo json_encode($data);
            }else{
                $this->session->set_flashdata('result', $data);
                redirect('admin');
            }
        }
        else
        {
            if($type == 'course'){
                $this->load->model('Courses_model');
                if($this->Courses_model->delete()){
                    $flag = TRUE;
                }else{
                    $flag = FALSE;
                }
            }elseif($type == 'file'){
                $this->load->model('Files_model');
                if($this->Files_model->delete()){
                    $flag = TRUE;
                }else{
                    $flag = FALSE;
                }
            }

            if($flag){

                $data['success'] = true;
                $data['message'] = $this->lang->line('delete_success');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }else{
                $data['success'] = false;
                $data['message'] = $this->lang->line('delete_fail');

                if($this->input->is_ajax_request()){
                    echo json_encode($data);
                }else{
                    $this->session->set_flashdata('result', $data);
                    redirect('admin');
                }
            }
        }
    }
}
