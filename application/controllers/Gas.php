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
class Gas extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

		$this->load->database();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 1000; // 100 requests per hour per user/key
		$this->methods['vender_get']['limit'] = 1000; // 100 requests per hour per user/key
        $this->methods['readgas_post']['limit'] = 1000; // 50 requests per hour per user/key
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
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			$sys_user = $this->it_model->listData("sys_user","comm_id='".$comm_id."' and app_id ='".$app_id."'");
			if($sys_user["count"]>0)
			{
				$sys_user = $sys_user["data"][0];
				
				$condition = "comm_id = '".$comm_id."' and building_id = '".$sys_user["addr"]."' ";				
				$gas_list = $this->it_model->listData( "gas" , $condition , NULL , NULL , array("created"=>"desc") );

				if ($gas_list['count'] > 0) 
				{
					$ajax_ary = array();
					foreach ($gas_list["data"] as $item_info) 
					{					
						$tmp_data = array
						(				
							"sn"=> $item_info["client_sn"],		
							"addr"=> tryGetData("building_text",$item_info),
							"year" => $item_info["year"],		
							"month" => $item_info["month"],	
							"degress" => $item_info["degress"]
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
	
	
	public function vender_get()
    {		
		$comm_id = tryGetData('comm_id', $_GET, NULL);		
		
		
        if( isNull($comm_id)) 
		{
            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			$condition = "comm_id = '".$comm_id."' ";
			$gas_company_info = $this->c_model->GetList( "gas_company", $condition);
		
			if($gas_company_info["count"]>0)
			{						
				$item_info = $gas_company_info["data"][0];
				$ajax_ary = array();
				
				$img_url = "";
				if(isNotNull(tryGetData("img_filename",$item_info)))
				{
					$img_url = $this->config->item("api_server_url")."upload/".$comm_id."/gas_company/".$item_info["img_filename"];
				}	
				
				$vender_str = '
				<table style="width: 100%;">
				<tbody>
					<tr>
						<td>公司名稱</td>
						<td>'.$item_info["title"].'</td>
					</tr>
					<tr>
						<td>地址</td>
						<td>'.$item_info["content"].'</td>
					</tr>
					<tr>
						<td>電話</td>
						<td>'.$item_info["brief"].'</td>
					</tr>
					<tr>
						<td>手機</td>
						<td>'.$item_info["brief2"].'</td>
					</tr>
					<tr>
						<td>網址</td>
						<td>'.$item_info["url"].'</td>
					</tr>					
				</tbody>
				</table>			
				';
				

				
				$tmp_data = array
				(				
					"vender_data"=> $vender_str,
					"img_url" => $img_url
				);						
				array_push($ajax_ary,$tmp_data);

			
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
	
	
	
	//抄表作業
    public function readgas_post()
    {		
		$comm_id = tryGetData('comm_id', $_POST, NULL);	
		$app_id = tryGetData('app_id', $_POST, NULL);
		$year = $this->input->post("year",TRUE);
		$month = $this->input->post("month",TRUE);
		$degress = $this->input->post("degress",TRUE);
		
		//dprint($_GET);
		
        if( isNull($comm_id) || isNull($app_id) || isNull($year) || isNull($month) || isNull($degress)) 
		{

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        }
		else 
		{
			$sys_user = $this->it_model->listData("sys_user","comm_id='".$comm_id."' and app_id ='".$app_id."'");
			if($sys_user["count"]>0)
			{
				$sys_user = $sys_user["data"][0];
				
				
				$update_data = array(
				"degress" => $degress,							
				"updated" => date( "Y-m-d H:i:s" ),
				"client_sync" => 0
				);
				
				$condition = "building_id = '".$sys_user["addr"]."' AND year = '".$year."' AND month = '".$month."' and comm_id='".$comm_id."'";
				$result = $this->it_model->updateData( "gas" , $update_data,$condition );					
				
				if($result === FALSE)
				{
					$update_data["comm_id"] = $comm_id;
					$update_data["building_id"] = $sys_user["addr"];
					$update_data["building_text"] = $sys_user["building_id"];
					$update_data["year"] = $year;
					$update_data["month"] = $month;
					$update_data["created"] = date( "Y-m-d H:i:s" );
					
					$content_sn = $this->it_model->addData( "gas" , $update_data );
					if($content_sn > 0)
					{
						$this->set_response(1, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
					}
					else
					{
						$this->set_response(0, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
					}
				}
				else
				{
					$this->set_response(1, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
				}				
			}
			else
			{
				$this->set_response(0, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
			}
			
		}
	}	

    

}
