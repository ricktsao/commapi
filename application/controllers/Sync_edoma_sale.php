<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync_edoma_sale extends CI_Controller {	
	
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
	public function getEdomaSale()
	{
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$condition = "comm_id = '".$comm_id."' and edoma_sn > 0 and client_sync = 0 ";			
		$edoma_list = $this->it_model->listData( "house_to_sale" , $condition );				
		echo json_encode($edoma_list["data"]);
	}
	

	public function updateEdomaSale()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		$edit_data["content"] = $this->input->post("content");	
		
		$arr_data = array
		(   
			"client_sync" =>  1
		);		
		
		if($this->it_model->updateData( "house_to_sale" , $arr_data, "sn = '".$edit_data["server_sn"]."' AND comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			echo '0';	
		}
	}
	
}
