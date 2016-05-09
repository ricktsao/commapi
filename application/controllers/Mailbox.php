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
class Mailbox extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

		$this->load->database();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 1000; // 100 requests per hour per user/key	
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
			$sys_user = $this->it_model->listData("sys_user","comm_id='".$comm_id."' and app_id ='".$app_id."'");
			if($sys_user["count"]>0)
			{
				$sys_user = $sys_user["data"][0];
				
				$condition = "comm_id = '".$comm_id."' and user_sn = '".$sys_user["sn"]."' ";		
			
			
				$mailbox_list = $this->it_model->listData( "mailbox" , $condition , NULL , NULL , array("updated"=>"desc") );

				if ($mailbox_list['count'] > 0) 
				{
					$ajax_ary = array();
					foreach ($mailbox_list["data"] as $item_info) 
					{					
						$tmp_data = array
						(				
							"sn"=> $item_info["client_sn"],		
							"mail_no"=> tryGetData("no",$item_info),
							"type" => tryGetData("type_str",$item_info),
							"memo" => tryGetData("desc",$item_info),					
							"post_date" => $item_info["updated"]
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
	
	

    

}
