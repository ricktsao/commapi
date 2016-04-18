<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class News extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

		$this->load->database();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['news_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['news_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['news_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function news_get($comm_id=NULL, $sn=NULL)
    {
        if ( isNull($comm_id) ) {

            $this->set_response([
                'status' => FALSE,
                'message' => '操作錯誤'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        } else {


			$result = $this->c_model->GetList('news');
			$news = $result['data'];


			// If the sn parameter doesn't exist return all the news
			if ( isNull($sn) ) {
				// Check if the news data store contains news (in case the database result returns NULL)
				if ($news)
				{
					// Set the response and exit
					$this->response($news, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

				} else {

					// Set the response and exit
					$this->response([
						'status' => FALSE,
						'message' => '找不到任何資訊'
					], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
				}
			}

			// Find and return a single record for a particular user.

			$sn = (int) $sn;

			// Validate the sn.
			if ($sn <= 0) {
				// Invalid sn, set the response and exit.
				$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			}

			// Get the user from the array, using the sn as key for retreival.
			// Usually a model is to be used for this.
			$new = NULL;

			if (!empty($news)) {
				foreach ($news as $key => $value) {
					if (isset($value['sn']) && $value['sn'] == $sn) {
						$new = $value;
					}
				}
			}

			if (!empty($new)) {
				$this->set_response($new, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			
			} else {
				$this->set_response([
					'status' => FALSE,
					'message' => '您指定的訊息無法顯示 #'.$sn
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    public function news_post()
    {
		$comm_id = $this->input->post("comm_id",TRUE);
		$r_row_count = $this->input->post("row_count",TRUE);		
		$row_count = 100;
		if( isNotNull($r_row_count) )
		{
			$row_count = $r_row_count;
		}
		
		
		if ( isNull($comm_id) ) 
		{
            $this->set_response([
                'status' => FALSE,
                'message' => '操作錯誤'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		
		
		$ajax_ary = array();
		$news_list = $this->c_model->GetList( "news" , "comm_id = '".$comm_id."'" ,TRUE, $row_count , 1 , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		if($news_list["count"]==0)
		{
			$this->response([
						'status' => FALSE,
						'message' => '找不到任何資訊'
					], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
		else
		{
			foreach ($news_list["data"] as $news_info) 
			{
				
				$tmp_data = array
				(				
					"sn"=> $news_info["sn"],
					"title"=> $news_info["title"],
					"content" => $news_info["content"],
					"news_date" =>  showDateFormat($news_info["start_date"])					
				);
				
				array_push($ajax_ary,$tmp_data);
			}
			
			$this->set_response($ajax_ary, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code			
		}        
    }

	
	
	public function news_detail_post()
    {
		$comm_id = $this->input->post("comm_id",TRUE);	
		$news_sn = $this->input->post("news_sn",TRUE);	
		
		if ( isNull($comm_id) || isNull($news_sn)) 
		{
            $this->set_response([
                'status' => FALSE,
                'message' => '操作錯誤'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		
		
		$ajax_ary = array();
		$news_info = $this->c_model->GetList( "news" , "client_sn = '".$news_sn."' and comm_id = '".$comm_id."'" ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		if($news_info["count"]==0)
		{
			$this->response([
						'status' => FALSE,
						'message' => '找不到任何資訊'
					], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
		else
		{
			$news_info = $news_info["data"][0];
			
			$ajax_ary = array
			(				
				"title"=> $news_info["title"],
				"content" => $news_info["content"],
				"news_date" =>  showDateFormat($news_info["start_date"])					
			);
			
			
			$this->set_response($ajax_ary, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code			
		}        
    }
	
	

    public function users_delete($comm_id, $sn)
    {
        // Validate the id.
        if ($sn <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'sn' => $sn,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
