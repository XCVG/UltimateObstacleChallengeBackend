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
	public function getDailyResultsById(int $student_id, date $date) {

		if ($student_id != null && $date != null) {
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
	public function getResultsByGenderGrade($gender, $grade) 
                {
                    if ($gender != null && $grade != null) {

                        $this->db->select('*');
                        $this->db->from('result');
                        $this->db->join('student', 'result.student_id = student.student_id');
                        $this->db->where(array('gender' => $gender, 'grade' => $grade));
                        $query = $this->db->get();
                        
                        //$query = 'result: ' . $gender . ' ' . $grade;
                        return $query;
                    }
  
     
                }

	//returns results by gender and grade on a specific day
	public function getDailyResultsByGenderGrade(char $gender, smallint $grade, date $date) {

		if ($gender == 'm' || $gender == 'f' && $grade != null && $date != null) {
			$results = $this->db->get_where('result', array('gender' => $gender, 'grade' => $grade, 'date' => $date));
			return $results;
		}
	}

	//returns results by gender and grade in the week
	public function getWeeklyResultsByGenderGrade(char $gender, smallint $grade) {

		if ($gender == 'm' || $gender == 'f' && $grade != null) {

			$this->db->where('gender' == $gender);
			$this->db->where('grade' == $grade);
			$this->db->where('date >=', date(y - m - d));
			$this->db->where('date <=', strtotime('-7 day' . date(y - m - d)));

			return $this->db->get('result');
		}
	}

	//returns results by gender and grade in the month
	public function getMonthlyResultsByGenderGrade(char $gender, smallint $grade) {

		if ($gender == 'm' || $gender == 'f' && $grade != null) {

			$this->db->where('gender' == $gender);
			$this->db->where('grade' == $grade);
			$this->db->where('date >=', strtotime('first day of this month' . date(y - m - d)));
			$this->db->where('date <=', strtotime('last day of this month' . date(y - m - d)));

			return $this->db->get('result');
		}
	}

	//creates a new database entry in the student table
	public function addStudent() {
		$data = array(
			'name' => $name,
			'gender' => $gender,
			'grade' => $grade,
			'student_id' => $student_id
		);

		$this->db->insert('student', $data);
	}

	//creates a new database entry in the result table
	public function addResult() {

		$data = array(
			'date' => $date,
			'time' => $time,
			'ranked' => $ranked,
			'flagged' => $flagged,
			'result_id' => $result_id,
			'student_id' => $student_id
		);

		$this->db->insert('result', $data);
	}

}
