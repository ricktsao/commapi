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
class Repair extends REST_Controller {

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
		
		//dprint($_GET);
		
        if( isNull($comm_id) || isNull($app_id)) 
		{

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			$condition = "comm_id = '".$comm_id."' and app_id = '".$app_id."'";			
			$repair_list = $this->it_model->listData( "repair" , $condition , NULL , NULL , array("created"=>"desc","sn"=>"desc") );

			if ($repair_list['count'] > 0) 
			{
				$ajax_ary = array();
				foreach ($repair_list["data"] as $repair_info) 
				{					
					$tmp_data = array
					(				
						"sn"=> $repair_info["client_sn"],
						"user_name" => $repair_info["user_name"],
						"repair_type"=> tryGetData($repair_info["type"],$this->config->item("repair_type")),
						"content" => $repair_info["content"],
						"post_date" =>  showDateFormat($repair_info["created"]),
						"repair_status" => tryGetData($repair_info["status"],$this->config->item("repair_status")),
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
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

			}
		}
	}
	
	public function detail_post()
    {		
		$comm_id = tryGetData('comm_id', $_POST, NULL);		
		$app_id = tryGetData('app_id', $_POST, NULL);
		$client_sn = tryGetData('sn', $_POST, NULL);
		
		//dprint($_GET);
		
        if( isNull($comm_id) || isNull($app_id) || isNull($client_sn)) 
		{
            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			$condition = "comm_id = '".$comm_id."' and app_id = '".$app_id."' and client_sn = '".$client_sn."'";			
			$repair_list = $this->it_model->listData( "repair" , $condition );
			if ($repair_list['count'] > 0) 
			{
				$repair_info = $repair_list['data'][0];
				
				$tmp_data = array
				(				
					"sn"=> $repair_info["client_sn"],
					"user_name" => $repair_info["user_name"],
					"repair_type"=> tryGetData($repair_info["type"],$this->config->item("repair_type")),
					"content" => $repair_info["content"],
					"post_date" =>  showDateFormat($repair_info["created"]),
					"repair_status" => tryGetData($repair_info["status"],$this->config->item("repair_status")),
				);
				
				$tmp_list = array();
				$repair_reply_list = $this->it_model->listData( "repair_reply" , "repair_sn = '".$repair_info["client_sn"]."'" );
				foreach ($repair_reply_list["data"] as $reply_info) 
				{					
					$tmp_reply = array
					(
						"reply_content" => $reply_info["reply"],
						"reply_date" =>  $reply_info["created"]
					);
					
					array_push($tmp_list,$tmp_reply);
				}
				
				
				$tmp_data["reply_list"] = $tmp_list;
				
				
				$ajax_ary = array();
				
				array_push($ajax_ary,$tmp_data);
			
				$this->set_response($ajax_ary, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
			} 
			else 
			{

				// Set the response and exit
				$this->response([
					'status' => FALSE,
					'message' => '找不到任何資訊，請確認'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

			}
		}
	}
	
	
	
	//報修
    public function event_post()
    {
		
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		//$client_sn = tryGetData('sn', $_POST, NULL);
		$app_id = tryGetData('app_id', $_POST, NULL);
		$repair_content = $this->input->post("repair_content",TRUE);
		$repair_type = $this->input->post("repair_type",TRUE);
		
		//dprint($_GET);
		
        if( isNull($comm_id) || isNull($app_id) || isNull($repair_content) || isNull($repair_type)) 
		{

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			$add_data = array(	
			"app_id" => $app_id,
			"type" => $repair_type,
			"content" => $repair_content,
			"updated" => date("Y-m-d H:i:s"),
			"created" => date("Y-m-d H:i:s")
			);
			
			$repair_sn = $this->it_model->addData( "repair" , $add_data );					
			if($repair_sn > 0)
			{
				//$add_data["sn"] = $repair_sn;
				//$add_data["comm_id"] = $this->getCommId();					
				//$this->sync_repair_to_server($add_data);
				$this->set_response(1, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
			}
			else
			{
				$this->set_response(0, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
			}
		}
	}

}
