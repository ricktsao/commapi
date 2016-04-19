<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync extends CI_Controller {


	
	
	function __construct() 
	{
		parent::__construct();	  
		$this->load->database();
	}
	
	
	
	public function index()
	{		
	
	}	


	public function updateContent()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}
		$edit_data["content"] = $this->input->post("content");	
		
		$arr_data = array
		(   
			  "parent_sn" => tryGetData("parent_sn",$edit_data,NULL)	
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
			, "sort" => tryGetData("sort",$edit_data,500)
			, "url" => tryGetData("url",$edit_data)
			, "target" => tryGetData("target",$edit_data,0)
			, "content" => tryGetData("content",$edit_data)
			, "update_date" =>  date( "Y-m-d H:i:s" )
		);
		
		
		if($this->it_model->updateData( "web_menu_content" , $arr_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			$arr_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$arr_data["client_sn"] = tryGetData("sn",$edit_data);
			$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "web_menu_content" , $arr_data );
			if($content_sn > 0)
			{		
				echo '1';		
			}
			else
			{
				echo '0';	
			}		
		}
	
		
	}
	
	
	function isNotNull($value) 
	{
		if(!isset($value))
		{
			return FALSE;
		}
		if (is_array($value)) 
		{
			if (sizeof($value) > 0) 
			{
				return true;
			} 
			else 
			{
				return false;
			}
		} 
		else 
		{
			if ( (is_string($value) || is_int($value) || is_float($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) 
			{
				return true;
			} 
			else 
			{
				return false;
			}
		}
	}
}
