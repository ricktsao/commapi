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
class Sys_news extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

		$this->load->database();
		
		$this->load->model("e_model");
		
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    
	
	
	public function index_get()
    {
		
		$comm_id = tryGetData('comm_id', $_GET, NULL);
		$sn = tryGetData('sn', $_GET, NULL);
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
			$condition = "comm_id like '%".$comm_id."%' ";
			if( isNotNull($_sn) )
			{
				$condition .= "and sn = '".$sn."'";
			}
			
			$news_list = $this->e_model->GetList( "sys_news" , $condition ,TRUE, NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
			if ($news_list['count'] > 0) 
			{
				$ajax_ary = array();
				foreach ($news_list["data"] as $news_info) 
				{
					$img_url = "";
					if(isNotNull(tryGetData("img_filename",$news_info)))
					{
						$img_url = $this->config->item("api_server_url")."upload/edoma/sys_news/".$news_info["img_filename"];
					}					
					
					$tmp_data = array
					(				
						"sn"=> $news_info["client_sn"],
						"title"=> $news_info["title"],
						"content" => $news_info["content"],
						"img_url" => $img_url,
						"news_date" =>  showDateFormat($news_info["start_date"])					
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

}
