<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync_edoma extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();	  
		$this->load->database();
	}
	
	
	public function index()
	{		
	
	}	
	
	/**
	 * 查詢由edoma平台新增的資料
	**/
	public function getEdomaContent()
	{
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$condition = "comm_id = '".$comm_id."' and client_sync = 0 ";			
		$edoma_list = $this->it_model->listData( "web_menu_content" , $condition );				
		echo json_encode($edoma_list["data"]);
	}
	

	public function updateEdomaContent()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		$edit_data["content"] = $this->input->post("content");	
		
		$arr_data = array
		(   
			  "client_sn" => tryGetData("sn",$edit_data,NULL)	
			, "title" => tryGetData("title",$edit_data)	
			, "brief" => tryGetData("brief",$edit_data)
			, "brief2" => tryGetData("brief2",$edit_data)	
			, "id" => tryGetData("id",$edit_data,NULL)	
			, "content_type" => tryGetData("content_type",$edit_data)
			, "start_date" => tryGetData("start_date",$edit_data,NULL)
			, "end_date" => tryGetData("end_date",$edit_data,NULL)
			, "forever" => tryGetData("forever",$edit_data,0)
			, "launch" => tryGetData("launch",$edit_data,0)			
			, "hot" => tryGetData("hot",$edit_data,0)
			, "del" => tryGetData("del",$edit_data,0)
			, "sort" => tryGetData("sort",$edit_data,500)
			, "url" => tryGetData("url",$edit_data)
			, "target" => tryGetData("target",$edit_data,0)
			, "content" => tryGetData("content",$edit_data)
			, "img_filename" => tryGetData("img_filename",$edit_data)
			, "update_date" =>  date( "Y-m-d H:i:s" )
			, "client_sync" =>  1
		);		
		
		if($this->it_model->updateData( "web_menu_content" , $arr_data, "sn = '".$edit_data["server_sn"]."' AND comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			echo '0';	
		}
	}
	
}
