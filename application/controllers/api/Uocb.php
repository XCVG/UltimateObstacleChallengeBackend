<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Uocb
 *
 * @author Richard Gosse
 */


class Uocb extends REST_Controller
{
    function __construct()
        {
            // Construct the parent class
            parent::__construct();

            // Configure limits on our controller methods
            // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
            $this->methods['result_get']['limit'] = 500; // 500 requests per hour per user/key
            $this->methods['result_post']['limit'] = 100; // 100 requests per hour per user/key
            $this->methods['result_delete']['limit'] = 50; // 50 requests per hour per user/key
            
            $this->load->model('result'); // load the result model
            
            

        }
        
        // todo: return a single result, when are we ever going to a have a single result?
//        public function result_get() 
//        {
//            
//            if(!$this->get('result_id'))
//                {
//                    $this->response(NULL, 400);
//                }
//            
//            //todo: access model and retrieve result   
//            $data = $this->result->get_result();
//                
//             $data = array('returned: '. $this->get('result_id')); // for debug, repeat result_id back 
//             
//             $this->response($data); // return data to the browser
//        }
        
        // todo: return top results such as for the leaderboard
        public function results_get()
        {
           //student_id - such as for 'my best times'
           // or
           //gender and grade
           //
           //period - daily, weekly (last 7 days), monthly(current month)
            
            
            if  ($this->get('student_id'))    
            {
                $studentID = (int)$this->get('student_id');
                $results = $this->result->getResultsById($studentID);
                $data = array(json_encode($results->result_array()));
                $this->response($results); // return data to the browser
                
            } 
            else 
            {
                if ($this->get('gender') && $this->get('grade'))
                {
                    $gender = $this->get('gender');
                    $grade = $this->get('grade');
                    $results = $this->result->getResultsByGenderGrade($gender, $grade);
                    $data = array(json_encode($results->result_array()));
                    $this->response($results); // return data to the browser
                    
                }
                else
                {
                   $this->response(NULL, 400); 
                }
                 
            }
                
        }

        // todo: add a result to the database
        public function result_post()
        {
            $post = $_POST;
            if ($this->input->post_get('date') &&
                  $this->input->post_get('student_id') &&
                  $this->input->post_get('time') &&
                  $this->input->post_get('ranked') &&
                  $this->input->post_get('flagged')
                    )
            {
            
                $date = $this->input->post_get('date');
                $student_id = $this->input->post_get('student_id');
                $time = $this->input->post_get('time');
                $ranked  = $this->input->post_get('ranked');
                $flagged = $this->input->post_get('flagged');
                    
                $data = array(
                    'date' => $date,
                    'student_id' => $student_id,
                    'time' => $time,
                    'ranked' => $ranked,
                    'flagged' => $flagged);
                
//                $data = array(
//                'date' => '2010-05-05',
//                'student_id' => '1',
//                'time' => '09.1234',
//                'ranked' => FALSE,
//                'flagged' => TRUE);
//                  
           
                $result = $this->result->addResult($data); 
                if ($result)
                {
                    $message = "Response: " . $result;
                 $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                }
            }
            else
            {
                // missing required parameters
            }
        }
        
        //remove a result, admin only
        public function result_delete()
        {
            
            // todo: Validate administration rights
            
            $result_id = (int) $this->get('result_id');

            // Validate the result_id.
            if ($result_id <= 0)
            {
                // Set the response and exit
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            // $this->some_model->delete_something($id);
            $message = [
                'result_id' => $result_id,
                'message' => 'Test Deleted the result'
            ];

            $this->response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
    
}
