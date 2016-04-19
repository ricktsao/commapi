<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    

    public function index_get()
    {
		$comm_id = $this->input->get("comm_id",TRUE);
		$sn = $this->input->get("sn",TRUE);	
		$r_row_count = $this->input->get("row_count",TRUE);			
		
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

	
	
	public function detail_post()
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
	
	

    public function index_delete($comm_id, $sn)
    {
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$sn = tryGetData('sn', $_POST, NULL);

        // Validate the id.
        if ( isNull($comm_id) && isNULL($sn) )
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
