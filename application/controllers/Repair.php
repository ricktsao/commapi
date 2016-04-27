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
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['event_post']['limit'] = 100; // 50 requests per hour per user/key
    }

    
	
	
	public function index_post()
    {
		
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$client_sn = tryGetData('sn', $_POST, NULL);
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
			$condition = "comm_id = '".$comm_id."' and to_user_app_id = '".$app_id."'";
			if( isNotNULL($client_sn) )
			{
				$condition .= "and client_sn = '".$client_sn."'";
			}
			
			$msg_list = $this->it_model->listData( "user_message" , $condition , NULL , NULL , array("created"=>"desc","sn"=>"desc") );

			if ($msg_list['count'] > 0) 
			{
				$ajax_ary = array();
				foreach ($msg_list["data"] as $msg_info) 
				{					
					$tmp_data = array
					(				
						"sn"=> $msg_info["client_sn"],
						"title"=> $msg_info["title"],
						"msg_content" => $msg_info["msg_content"],
						"send_date" =>  showDateFormat($msg_info["created"])					
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
