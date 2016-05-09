<?php

/*
 * @author jotiLalli, Richard Gosse
 */

class Result extends CI_Model {

	// Constructor
	public function __construct() {
		parent::__construct();
	}
        
	//returns all results by the gender and grade
	public function getResults($parameters)
                {
            
            $this->db->select('*');
            $this->db->from('result');
            $this->db->where($parameters);
            $this->db->order_by('time', "asc");
            $this->db->limit(10);
            $result = $this->db->get();
            return $result->result_array();
		
	}
        
        public function getDailyResults($parameters) 
                {
            $this->db->select('*');
            $this->db->from('result');
            $this->db->where($parameters);
            $this->db->where('date',Date('Y-m-d'));
            $this->db->order_by('time', "asc");
            $this->db->limit(10);
            $result = $this->db->get();
            return $result->result_array();;
            
        }
        
        public function getWeeklyResults($parameters) 
                {
            
            $this->db->select('*');
            $this->db->from('result');
            $this->db->where($parameters);
            $this->db->where('date <=',Date('Y-m-d'));
            $this->db->where('date >=',Date('Y-m-d', strtotime('-7 days')));
            $this->db->order_by('time', "asc");
            $this->db->limit(10);
            $result = $this->db->get();
            return $result->result_array();;
            
  
        }
        
        public function getMonthlyResults($parameters) 
                {
            $this->db->select('*');
            $this->db->from('result');
            $this->db->where($parameters);
            $this->db->where('date <=',Date('Y-m-d'));
            $this->db->where('date >=',Date('Y-m-d', strtotime('first day of this month'))); 
            $this->db->order_by('time', "asc");
            $this->db->limit(10);
            $result = $this->db->get();
            return $result->result_array();;
        }


	//creates a new database entry in the result table
	public function addResult($parameters) {

		$date = $parameters['date'];
		$time = $parameters['time'];
		$ranked = $parameters['ranked'];
		$flagged = $parameters['flagged'];
		$student_name = $parameters['student_name'];
                $student_gender = $parameters['student_gender'];
                $student_grade = $parameters['student_grade'];
                $school_name = $parameters['school_name'];

                if ($student_gender == 'M' | $student_gender == 'F'
                        )
                {
                
		$data = array(
			'date' => $date,
			'time' => $time,
			'ranked' => $ranked,
			'flagged' => $flagged,
			'student_name' => $student_name,
                        'student_gender' => $student_gender,
                        'student_grade' => $student_grade,
                        'school_name' => $school_name
		);

		return $this->db->insert('result', $data);
                }
                else {
                    return FALSE;
                }
	}

	public function deleteResult($result_id) {
		if ($result_id > 0) {
			$this->db->where('result_id', $result_id);
			$this->db->delete('result');
			return array('affected_rows' => $this->db->affected_rows());
		}
	}

}