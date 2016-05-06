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

        public function results_get($period = null)
        {
           switch ($period) {
               case "daily":
                   $where = $this->input->get();
                    $results = $this->result->getDailyResults($where);
                    $this->response($results); // return data to the browser
                   break;
               case "weekly":
                   $where = $this->input->get();
                    $results = $this->result->getWeeklyResults($where);
                    $this->response($results); // return data to the browser
                   break;
               case "monthly":
                   $where = $this->input->get();
                    $results = $this->result->getMonthlyResults($where);
                    $this->response($results); // return data to the browser
                   break;
               default:
                    $where = $this->input->get();
                    $results = $this->result->getResults($where);
                    $this->response($results); // return data to the browser  
           }
            }
                
        

        public function result_post()
        {
            if (
                  $this->input->post_get('date') &&
                  $this->input->post_get('time') &&
                  $this->input->post_get('ranked') &&
                  $this->input->post_get('flagged') &&
                  $this->input->post_get('student_name') &&
                  $this->input->post_get('student_gender') &&
                  $this->input->post_get('student_grade') &&
                  $this->input->post_get('school_name'))         
            {
                $data = array(
                    'date' => $this->input->post_get('date'),
                    'time' => $this->input->post_get('time'),
                    'ranked' => $this->input->post_get('ranked'),
                    'flagged' => $this->input->post_get('flagged'),
                    'student_name' => $this->input->post_get('student_name'),
                    'student_gender' => $this->input->post_get('student_gender'),
                    'student_grade' => $this->input->post_get('student_grade'),
                    'school_name' => $this->input->post_get('school_name'));

                $result = $this->result->addResult($data); 
                if ($result)
                {
                    $this->set_response($result, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
                }
            }
            else
            {
                // missing required parameters
                $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }
        }
        
        //remove a result, admin only
        public function result_delete()
        {
            
            // todo: Validate administration rights
            
            $result_id = $this->input->post_get('result_id');

            // Validate the result_id
            if ($result_id <= 0)
            {   
                $this->response($result_id, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }
            else 
                {

                    $response = $this->result->deleteResult($result_id);
                    if ($response['affected_rows'] > 0)
                        {
                            $message = 'Deleted: ' . $response['affected_rows'] . ' ' . $result_id;
                        }
                        else {
                            $message = 'Failed to Delete: ' . $response['affected_rows'] . ' ' . $result_id;
                         }
                    $this->response($message, REST_Controller::HTTP_OK); 
            }
        }
    
}
