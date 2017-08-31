<?php

class Files_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    function getCoursesFiles($course_id, $client = TRUE)
    {
        $this->db->select('id, name, size, download, created_at, description, active')->where('course_id', $course_id);
        if($client){
            $this->db->where('active', 1);
        }
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('files')->result_array();
    }

    function downloadCounter($course_id, $fileName)
    {
        $this->db->set('download', 'download+1', FALSE);
        $this->db->where('course_id', $course_id);
        $this->db->where('name', $fileName);
        $this->db->update('files');
    }

    function addFile($fileName, $fileSize)
    {
        $course_id = $this->input->post('course_id', true);
        $description = $this->input->post('description', true);

        $this->db->set('course_id', $course_id);
        $this->db->set('name', $fileName);
        $this->db->set('size', $fileSize);
        $this->db->set('description', $description);
        return $this->db->insert('files');
    }

    function editFile()
    {
        $file_id = $this->input->post('file_id', true);
        $course_id = $this->input->post('course_id', true);
        $name = $this->input->post('name', true);
        $download = $this->input->post('download', true);
        $description = $this->input->post('description', true);

        $this->db->select('name');
        $this->db->where('id', $file_id);
        $old_name = $this->db->get('files')->row_array();

        $this->db->set('name', $name);
        $this->db->set('download', $download);
        $this->db->set('description', $description);
        $this->db->where('id', $file_id);
        $result_db = $this->db->update('files');

        if($result_db){
            $old_path = DLPATH . 'course' . $course_id . '/' . $old_name['name'];
            $new_path = DLPATH . 'course' . $course_id . '/' . $name;
            if (is_file($old_path) === true)
                $result_file = rename($old_path, $new_path);

            if($result_db && $result_file){
                return 3;
            }elseif($result_db){
                return 2;
            }elseif($result_file){
                return 1;
            }
        }else{
            return 0;
        }
    }

    function changeActive(){
        $id = $this->input->post('file_id', true);
        $this->db->set('active', '!active', FALSE);
        $this->db->where('id', $id);
        return $this->db->update('files');
    }

    function delete(){

        $id = $this->input->post('file_id', true);

        $this->db->select('course_id, name')->where('id', $id);
        $file_info = $this->db->get('files')->row_array();

        $result_db = $this->db->delete('files', array('id' => $id));

        if($result_db) {
            $path = DLPATH . 'course' . $file_info['course_id'] . '/' . $file_info['name'];
            if (is_file($path) === true)
                $result_file = unlink($path);

            if ($result_file) {
                return 2;
            } else {
                return 1;
            }
        } else {
            return 0;
        }
    }
}