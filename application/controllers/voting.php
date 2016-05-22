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
class Voting extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

		$this->load->database();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['detail_get']['limit'] = 500; // 500 requests per hour per user/key        
        $this->methods['result_get']['limit'] = 500; // 100 requests per hour per user/key
        $this->methods['result_detail_get']['limit'] = 500; // 50 requests per hour per user/key
        $this->methods['voting_post']['limit'] = 500; // 50 requests per hour per user/key


		$this->load->model('Voting_model');	
    }

    
	
	
	public function index_get()
    {
		
		$comm_id = tryGetData('comm_id', $_GET, NULL);
		$app_id  = tryGetData('app_id', $_GET, NULL);
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

			$list = $this->Voting_model->frontendGetVotingList($app_id,$comm_id);

			if ($list['count'] > 0) 
			{
				$ajax_ary = array();
				
				$ajax_ary = $list['data'];
			
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

	public function detail_get(){

		$comm_id = tryGetData('comm_id', $_GET, NULL);
		$sn  = tryGetData('sn', $_GET, NULL);

		if( isNull($comm_id) || isNull($sn)) 
		{

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{		

			$query = "SELECT client_sn as voting_sn,
					subject,
					description,
					start_date,
					end_date,
					is_multiple,
					user_sn
					FROM voting WHERE client_sn=".$sn." AND is_del = 0 AND comm_id ='".$comm_id."'";

			$voting = $this->it_model->runSql($query);

			

			if($voting['count']>0){

				$voting = $voting['data'][0];

				//get post_user
				if($voting['user_sn']!=''){

					$query = "SELECT name FROM sys_user WHERE client_sn = ".$voting['user_sn']." AND comm_id='".$comm_id."'";
					$post_user = $this->it_model->runSql($query);
					if($post_user['count'] > 0){
						$voting['creater_user'] = 	$post_user['data'][0]['name'];
					}else{
						$voting['creater_user'] = NULL;
					}
				}

				unset($voting['user_sn']);


				$query = "SELECT 
						client_sn as option_sn,
						text
						
						FROM voting_option
						WHERE voting_sn = ".$sn." AND is_del = 0 AND comm_id ='".$comm_id."'";

				$list = $this->it_model->runSql($query);

				$voting['option'] = $list['data'];

				$this->set_response($voting, REST_Controller::HTTP_OK);



			}else{

				$this->set_response([
	                'status' => FALSE,
	                'message' => '缺少必要資料，請確認'
	            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}


		}



	}


	public function result_get(){
		$comm_id = tryGetData('comm_id', $_GET, NULL);
		
		//dprint($_GET);		
        if( isNull($comm_id)) 
		{

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			
			$list = $this->Voting_model->frontendGetVotingResultList($comm_id);		
			if ($list['count'] > 0) 
			{
				
				$ajax_ary = $list['data'];
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

	public function result_detail_get(){
		$comm_id = tryGetData('comm_id', $_GET, NULL);
		$sn = tryGetData('sn', $_GET, NULL);


		if( isNull($comm_id) || isNull($sn)) 
		{

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			
			$list = $this->Voting_model->votingRecord($sn,$comm_id);

			if ($list) 
			{
				
				$ajax_ary = $list;
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


	public function voting_post(){

		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$option_sn = tryGetData('option_sn', $_POST, NULL);
		$voting_sn = tryGetData('voting_sn', $_POST, NULL);
		$app_id =  tryGetData('app_id', $_POST, NULL);
		



		if(isNull($comm_id) || isNull($option_sn) || isNull($voting_sn) || isNull($app_id)){

			 $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

		}else{
			//get user_sn
			//get user
			$query = "SELECT client_sn,id FROM sys_user WHERE comm_id='".$comm_id."' AND app_id ='".$app_id."'";
			$member_sn = $this->it_model->runSql($query);

			if($member_sn['count']==0){
				 $this->set_response([
	                'status' => FALSE,
	                'message' => '找不到此用戶資訊'
	            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}

			$member_id =  $member_sn['data'][0]['id'];
			$member_sn = $member_sn['data'][0]['client_sn'];

			$option_sn = explode(",",$option_sn);

			for($i=0;$i<count($option_sn);$i++){

				 $this->Voting_model->frontendGetVotingUpdate($voting_sn,$option_sn[$i],$member_sn ,$member_id,$comm_id);

			}

			
			$this->set_response(array("success"=>1), REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
		}



	} 

}
