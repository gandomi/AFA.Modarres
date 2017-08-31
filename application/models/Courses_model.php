<?php

class Courses_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    function getCourses($client = TRUE)
    {
        $this->db->select('id, name, created_at, active');
        if($client){
            $this->db->where('active', 1);
        }
        $this->db->order_by('ordering', 'ASC');
        return $this->db->get('courses')->result_array();
    }

    function addCourse()
    {
        $name = $this->input->post('name', true);
        $this->db->select('MAX(ordering)+1 as ordering');
        $ordering = $this->db->get('courses')->row_array();
        if(!$ordering){
            return false;
        }
        if($ordering['ordering'] === NULL){
            $ordering['ordering'] = 1;
        }

        $this->db->set('name', $name);
        $this->db->set('ordering', $ordering['ordering']);
        return $this->db->insert('courses');
    }

    function editCourse()
    {
        $id = $this->input->post('course_id', true);
        $name = $this->input->post('name', true);

        $this->db->set('name', $name);
        $this->db->where('id', $id);
        return $this->db->update('courses');
    }

    function changeActive(){
        $id = $this->input->post('course_id', true);
        $this->db->set('active', '!active', FALSE);
        $this->db->where('id', $id);
        return $this->db->update('courses');
    }

    function delete(){
        $id = $this->input->post('course_id', true);
        $result_db = $this->db->delete('courses', array('id' => $id));

        if($result_db) {
            $path = DLPATH . 'course' . $id;
            if (is_dir($path) === true) {
                $files = array_diff(scandir($path), array('.', '..'));

                foreach ($files as $file) {
                    $filePath = $path . '/' . $file;

                    if (is_file($filePath) === true)
                        unlink($filePath);
                }

                $result_dir = rmdir($path);
            }

            if ($result_dir) {
                return 2;
            } else {
                return 1;
            }
        } else {
            return 0;
        }
    }
}