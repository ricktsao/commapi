<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync_voting extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();	  
		$this->load->database();
	}	
	
	public function index()
	{		
	
	}	

	//更新投票主體
	public function updateContent()
	{		
		
		$edit_data = [];

		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		
		$comm_id = tryGetData("comm_id",$edit_data);

		unset($edit_data['$comm_id']);
		
		
		
		if($this->it_model->updateData( "voting" , $edit_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".$comm_id."'"))
		{					
			echo '1';						
		}
		else 
		{
			$edit_data["comm_id"] = $comm_id;
			$edit_data["client_sn"] = $edit_data['sn'];
			unset($edit_data['sn']);
			//$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "voting" , $edit_data );
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

	//刪除投票主體
	public function removeVoting(){
		$edit_data = [];
		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		$query ="UPDATE voting  SET is_del=1 WHERE comm_id='".$edit_data['comm_id']."' AND client_sn in (".$edit_data["sn"].")";

		$this->it_model->runSqlCmd($query);

		echo "1";
	}

	//更新投票項目之前的程序
	public function preUpdateOption(){
		$edit_data = [];
		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		$query ="UPDATE voting 
				LEFT JOIN voting_option ON voting.client_sn = voting_option.voting_sn
				SET voting_option.is_del = 1
				WHERE voting.client_sn = ".$edit_data["sn"]." AND voting_option.comm_id = '".$edit_data['comm_id']."'";

		$this->it_model->runSqlCmd($query);	
	}

	//投票項目
	public function updateOption()
	{		
		
		$edit_data = [];

		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		
		$comm_id = tryGetData("comm_id",$edit_data);

		unset($edit_data['$comm_id']);
		
		
		
		if($this->it_model->updateData( "voting_option" , $edit_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".$comm_id."'"))
		{					
			echo '1';						
		}
		else 
		{
			$edit_data["comm_id"] = $comm_id;
			$edit_data["client_sn"] = $edit_data['sn'];
			unset($edit_data['sn']);
			//$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "voting_option" , $edit_data );
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
	
	//前台使用者投票
	public function userVoting(){
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}

		$edit_data['is_sync'] = 1;
		$content_sn = $this->it_model->addData( "voting_record" , $edit_data );
		if($content_sn > 0){
			echo "1";
		}else{
			echo "0";
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
