<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync_album extends CI_Controller {	
	
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

		
		
		$sn = tryGetData("sn",$edit_data);
		$comm_id = tryGetData("comm_id",$edit_data);
		unset($edit_data['sn']);
		unset($edit_data['$comm_id']);	
		
		
		
		if($this->it_model->updateData( "album" , $edit_data, "client_sn ='".$sn."' and comm_id = '".$comm_id."'"))
		{					
			echo '1';						
		}
		else 
		{
			$edit_data["comm_id"] = $comm_id;
			$edit_data["client_sn"] = $sn;
			
			//$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "album" , $edit_data );
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

	//刪除相簿
	public function removeAlbum(){
		$edit_data = [];
		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		$query ="UPDATE album  SET is_del=1 WHERE comm_id='".$edit_data['comm_id']."' AND client_sn in (".$edit_data["sn"].")";

		$this->it_model->runSqlCmd($query);

		echo "1";
	}

	

	//投票項目
	public function updatePhoto()
	{		
		
		$edit_data = [];

		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		
		$sn = tryGetData("sn",$edit_data);
		$comm_id = tryGetData("comm_id",$edit_data);
		unset($edit_data['sn']);
		unset($edit_data['$comm_id']);		
		
		if($this->it_model->updateData( "album_item" , $edit_data, "client_sn ='".$sn."' and comm_id = '".$comm_id."'"))
		{					
			echo '1';						
		}
		else 
		{
			$edit_data["comm_id"] = $comm_id;
			$edit_data["client_sn"] = $sn;
		
			//$arr_data["create_date"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "album_item" , $edit_data );
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

		//刪除相簿
	public function removeAlbumItem(){
		$edit_data = [];
		foreach ($_POST as $key => $value) {
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

		$query ="UPDATE album_item  SET is_del=1 WHERE comm_id='".$edit_data['comm_id']."' AND client_sn in (".$edit_data["sn"].")";

		$this->it_model->runSqlCmd($query);

		echo "1";
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
