<?php

/*
 * @author jotiLalli
 */

class Result extends CI_Model {

	// Constructor
	public function __construct() {
		parent::__construct();
	}

	//returns all results by student id
	public function getResultsById($student_id) {

		if ($student_id != null) {

			$results = $this->db->get_where('result', array('student_id' => $student_id));

			//return 'test: ' . $student_id;
			return $results;
		}
	}

	//returns all results by student id and date
	public function getDailyResultsById($parameters) {
		$student_id = $parameters['student_id'];
		$date = $parameters['date'];

		if ($student_id != null && $date != null) {
			$data = array(
			'student_id' => $student_id,
			'date' => $date
		);
			$results = $this->db->get_where('result', array('student_id' => $student_id, 'date' => $date));
			return $results;
		}
	}

	//returns all results by student id and within a week
	public function getWeeklyResultsById(int $student_id) {

		if ($student_id != null) {

			$this->db->where('student_id' == $student_id);
			$this->db->where('date >=', date(y - m - d));
			$this->db->where('date <=', strtotime('-7 day' . date(y - m - d)));

			return $this->db->get('result');
		}
	}

	//returns all results by student id and within the current month
	public function getMonthlyResultsById($student_id) {

		if ($student_id != null) {

			$this->db->where('student_id' == $student_id);
			$this->db->where('date >=', strtotime('first day of this month' . date(y - m - d)));
			$this->db->where('date <=', strtotime('last day of this month' . date(y - m - d)));

			return $this->db->get('result');
		}
	}

	//returns all results by the gender and grade
	public function getResultsByGenderGrade($parameters) {
		$student_gender = $parameters['student_gender'];
		$student_grade = $parameters['student_grade'];

		if ($student_gender != null && $student_grade != null) {
			$data = array(
				'student_gender' => $student_gender,
				'student_grade' => $student_grade
			);
			$this->db->select('*');
			$this->db->from('result');
			$this->db->join('student', 'result.student_id = student.student_id');
			$this->db->where(array('student_gender' => $student_gender, 'student_grade' => $student_grade));
			$query = $this->db->get();

			//$query = 'result: ' . $gender . ' ' . $grade;
			return $query;
		}
	}

	//returns results by gender and grade on a specific day
	public function getDailyResultsByGenderGrade($parameters) {
		$student_gender = $parameters['student_gender'];
		$student_grade = $parameters['student_grade'];
		$date = $parameters['date'];
		if ($student_gender == 'm' || $student_gender == 'f' && $student_grade != null && $date != null) {
			$data = array(
				'student_gender' => $student_gender,
				'student_grade' => $student_grade,
				'date' => $date
			);
		}
		return $this->db->get($data);
	}

	//returns results by gender and grade in the week
	public function getWeeklyResultsByGenderGrade($parameters) {
		$student_gender = $parameters['student_gender'];
		$student_grade = $parameters['student_grade'];

		if ($student_gender == 'm' || $student_gender == 'f' && $student_grade != null) {
			$data = array(
				'student_gender' => $student_gender,
				'student_grade' => $student_grade
			);
			$this->db->where('student_gender' == $student_gender);
			$this->db->where('student_grade' == $student_grade);
			$this->db->where('date >=', date(y - m - d));
			$this->db->where('date <=', strtotime('-7 day' . date(y - m - d)));

			return $this->db->get('result');
		}
	}

	//returns results by gender and grade in the month
	public function getMonthlyResultsByGenderGrade($parameters) {
		$student_gender = $parameters['student_gender'];
		$student_grade = $parameters['student_grade'];

		if ($student_gender == 'm' || $student_gender == 'f' && $student_grade != null) {
			$data = array(
				'student_gender' => $student_gender,
				'student_grade' => $student_grade
			);
			$this->db->where('student_gender' == $student_gender);
			$this->db->where('student_grade' == $student_grade);
			$this->db->where('date >=', strtotime('first day of this month' . date(y - m - d)));
			$this->db->where('date <=', strtotime('last day of this month' . date(y - m - d)));

			return $this->db->get('result');
		}
	}

	//creates a new database entry in the student table
	public function addStudent() {
		$data = array(
			'name' => $student_name,
			'gender' => $student_gender,
			'grade' => $student_grade,
			'student_id' => $student_id
		);

		$this->db->insert('student', $data);
	}

	//creates a new database entry in the result table
	public function addResult($parameters) {

		$date = $parameters['date'];
		$time = $parameters['time'];
		$ranked = $parameters['ranked'];
		$flagged = $parameters['flagged'];
		$student_id = $parameters['student_id'];

		$data = array(
			'date' => $date,
			'time' => $time,
			'ranked' => $ranked,
			'flagged' => $flagged,
			//'result_id' => $result_id, auto-assigned
			'student_id' => $student_id
		);

		return $this->db->insert('result', $data);
	}

	public function deleteRecord($result_id) {
		if ($result_id > 0) {
			$this->db->where('result_id', $result_id);
			$this->db->delete('result');
			return array('affected_rows' => $this->db->affected_rows());
		}
	}

}