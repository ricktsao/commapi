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
class Suggestion extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

		$this->load->database();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 1000; // 100 requests per hour per user/key
		$this->methods['detail_post']['limit'] = 1000; // 100 requests per hour per user/key
        $this->methods['event_post']['limit'] = 1000; // 50 requests per hour per user/key
    }

    
	
	
	public function index_post()
    {		
		$comm_id = tryGetData('comm_id', $_POST, NULL);		
		$app_id = tryGetData('app_id', $_POST, NULL);
		$client_sn = tryGetData('sn', $_POST, NULL);
		
		//dprint($_GET);
		
        if( isNull($comm_id) || isNull($app_id)) 
		{

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			$condition = "comm_id = '".$comm_id."' and app_id = '".$app_id."' ";		
			if(isNotNull($client_sn))
			{
				$condition .= "and client_sn='".$client_sn."'";
			}
			
			$suggestion_list = $this->it_model->listData( "suggestion" , $condition , NULL , NULL , array("created"=>"desc","sn"=>"desc") );

			if ($suggestion_list['count'] > 0) 
			{
				$ajax_ary = array();
				foreach ($suggestion_list["data"] as $item_info) 
				{					
					$tmp_data = array
					(				
						"sn"=> $item_info["client_sn"],		
						"title" => $item_info["title"],		
						"content" => $item_info["content"],	
						"reply" => $item_info["reply"],							
						"to_role"=> tryGetData($item_info["to_role"],$this->config->item("suggestion_to_role")),						
						"post_date" =>  showDateFormat($item_info["created"])					
					);
					
					array_push($ajax_ary,$tmp_data);
				}
			
				$this->set_response($ajax_ary, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
			} 
			else 
			{

				// Set the response and exit
				$this->response([
					'status' => FALSE,
					'message' => '找不到任何資訊，請確認'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

			}
		}
	}
	
	
	//意見箱
    public function event_post()
    {		
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		//$client_sn = tryGetData('sn', $_POST, NULL);
		$app_id = tryGetData('app_id', $_POST, NULL);
		$title = $this->input->post("title",TRUE);
		$content = $this->input->post("content",TRUE);
		$to_role = $this->input->post("to_role",TRUE);
		
		//dprint($_GET);
		
        if( isNull($comm_id) || isNull($app_id) || isNull($title) || isNull($content) || isNull($to_role)) 
		{

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			$add_data = array(				
			"comm_id"=> $comm_id,
			"app_id"=> $app_id,			
			"title"  => $title,
			"content" => $content,
			"to_role" => $to_role,
			"client_sync" => 0,
			"updated" => date("Y-m-d H:i:s"),
			"created" => date("Y-m-d H:i:s")
			);
			
			$suggestion_sn = $this->it_model->addData( "suggestion" , $add_data );					
			if($suggestion_sn > 0)
			{
				$this->set_response(1, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
			}
			else
			{
				$this->set_response(0, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
			}
		}
	}	

    

}
