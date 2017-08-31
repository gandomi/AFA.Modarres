<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->model('Courses_model');
		$this->load->model('Files_model');

		$courses = $this->Courses_model->getCourses();
		for($i = 0; $i < count($courses); $i++){
			$courses[$i]['files'] = $this->Files_model->getCoursesFiles($courses[$i]['id']);
			$timestamp = explode(' ', $courses[$i]['created_at']);
			$date = explode('-', $timestamp[0]);
			$courses[$i]['created_at'] = $this->jdf->gregorian_to_jalali($date[0], $date[1], $date[2], ' - ').'&nbsp;&nbsp;&nbsp;&nbsp;'.$timestamp[1];
		}
		$data['all'] = $courses;
//        echo '<pre>';
//        exit(print_r($courses));
		$this->load->view('home', $data);
	}
}
