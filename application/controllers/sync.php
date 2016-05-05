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
			, "del" => tryGetData("del",$edit_data,0)
			, "sort" => tryGetData("sort",$edit_data,500)
			, "url" => tryGetData("url",$edit_data)
			, "target" => tryGetData("target",$edit_data,0)
			, "content" => tryGetData("content",$edit_data)
			, "img_filename" => tryGetData("img_filename",$edit_data)
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
	
	
	public function updateUserMessage()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			  "edit_user_sn" => tryGetData("edit_user_sn",$edit_data,NULL)	
			, "to_user_sn" => tryGetData("to_user_sn",$edit_data)	
			, "to_user_app_id" => tryGetData("to_user_app_id",$edit_data)
			, "to_user_name" => tryGetData("to_user_name",$edit_data)	
			, "title" => tryGetData("title",$edit_data,NULL)	
			, "msg_content" => tryGetData("msg_content",$edit_data)			
			, "updated" =>  date( "Y-m-d H:i:s" )
		);
		
		
		if($this->it_model->updateData( "user_message" , $arr_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			$arr_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$arr_data["client_sn"] = tryGetData("sn",$edit_data);
			$arr_data["created"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "user_message" , $arr_data );
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
	
	
	public function updateRepair()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			  "user_sn" => tryGetData("edit_user_sn",$edit_data,NULL)	
			, "user_name" => tryGetData("user_name",$edit_data)	
			, "app_id" => tryGetData("app_id",$edit_data)
			, "type" => tryGetData("type",$edit_data)			
			, "status" => tryGetData("status",$edit_data,0)
			, "content" => tryGetData("content",$edit_data,NULL)		
			, "updated" =>  date( "Y-m-d H:i:s" )
		);
		
		
		if($this->it_model->updateData( "repair" , $arr_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			$arr_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$arr_data["client_sn"] = tryGetData("sn",$edit_data);
			$arr_data["created"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "repair" , $arr_data );
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
	
	public function updateRepairReply()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			  "repair_sn" => tryGetData("repair_sn",$edit_data,NULL)	
			, "repair_status" => tryGetData("repair_status",$edit_data)	
			, "reply" => tryGetData("reply",$edit_data)				
			, "updated" =>  date( "Y-m-d H:i:s" )
		);
		
		
		if($this->it_model->updateData( "repair_reply" , $arr_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			$arr_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$arr_data["client_sn"] = tryGetData("sn",$edit_data);
			$arr_data["created"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "repair_reply" , $arr_data );
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
	
	
	/**
	 * 查詢由app新增的報修資料
	**/
	public function getAppRepair()
	{
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$condition = "comm_id = '".$comm_id."' and client_sync = 0 ";			
		$repair_list = $this->it_model->listData( "repair" , $condition );
				
		echo json_encode($repair_list["data"]);
	}
	
	
	public function updateServerRepair()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			  "client_sn" => tryGetData("sn",$edit_data,NULL)	
			, "user_sn" => tryGetData("user_sn",$edit_data,NULL)	
			, "user_name" => tryGetData("user_name",$edit_data)	
			, "app_id" => tryGetData("app_id",$edit_data)
			, "type" => tryGetData("type",$edit_data)			
			, "status" => tryGetData("status",$edit_data,0)
			, "content" => tryGetData("content",$edit_data,NULL)		
			, "updated" =>  date( "Y-m-d H:i:s" )
			, "client_sync" =>1
		);		
		
		if($this->it_model->updateData( "repair" , $arr_data, "sn ='".$edit_data["server_sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			echo '0';
		}
	}
	
	public function updateSuggestion()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			  "title" => tryGetData("title",$edit_data,NULL)
			, "content" => tryGetData("content",$edit_data,NULL)
			, "app_id" => tryGetData("app_id",$edit_data,NULL)
			, "reply" => tryGetData("reply",$edit_data,NULL)			
			, "user_sn" => tryGetData("edit_user_sn",$edit_data,NULL)	
			, "to_role" => tryGetData("to_role",$edit_data)			
			, "updated" =>  date( "Y-m-d H:i:s" )
		);
		
		
		if($this->it_model->updateData( "suggestion" , $arr_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			$arr_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$arr_data["client_sn"] = tryGetData("sn",$edit_data);
			$arr_data["created"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "suggestion" , $arr_data );
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
	
	/**
	 * 查詢由app新增的意見箱
	**/
	public function getAppSuggestion()
	{
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$condition = "comm_id = '".$comm_id."' and client_sync = 0 ";			
		$suggestion_list = $this->it_model->listData( "suggestion" , $condition );
				
		echo json_encode($suggestion_list["data"]);
	}	
	
	public function updateServerSuggestion()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			  "client_sn" => tryGetData("sn",$edit_data,NULL)
			, "reply" => tryGetData("reply",$edit_data,NULL)
			, "user_sn" => tryGetData("user_sn",$edit_data,NULL)
			, "to_role" => tryGetData("to_role",$edit_data)				
			, "updated" =>  date( "Y-m-d H:i:s" )
			, "client_sync" =>1
		);		
		
		if($this->it_model->updateData( "suggestion" , $arr_data, "sn ='".$edit_data["server_sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			echo '0';
		}
	}
	
	
	
	
	
	public function updateGas()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			  "building_id" => tryGetData("building_id",$edit_data,NULL)
			, "building_text" => tryGetData("building_text",$edit_data,NULL)	
			, "year" => tryGetData("year",$edit_data,NULL)				
			, "month" => tryGetData("month",$edit_data,NULL)	
			, "degress" => tryGetData("degress",$edit_data)			
			, "updated" =>  date( "Y-m-d H:i:s" )
		);
		
		
		if($this->it_model->updateData( "gas" , $arr_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			$arr_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$arr_data["client_sn"] = tryGetData("sn",$edit_data);
			$arr_data["created"] =   date( "Y-m-d H:i:s" );
			$content_sn = $this->it_model->addData( "gas" , $arr_data );
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
	
	
	
	
	/**
	 * 查詢由app新增的瓦斯資料
	**/
	public function getAppGas()
	{
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$condition = "comm_id = '".$comm_id."' and client_sync = 0 ";			
		$gas_list = $this->it_model->listData( "gas" , $condition );
				
		echo json_encode($gas_list["data"]);
	}	
	
	public function updateServerGas()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			  "client_sn" => tryGetData("sn",$edit_data,NULL)
			, "building_id" => tryGetData("building_id",$edit_data)
			, "building_text" => tryGetData("building_text",$edit_data)
			, "year" => tryGetData("year",$edit_data,NULL)
			, "month" => tryGetData("month",$edit_data)
			, "degress" => tryGetData("degress",$edit_data)				
			, "updated" =>  date( "Y-m-d H:i:s" )
			, "client_sync" =>1
		);		
		
		if($this->it_model->updateData( "gas" , $arr_data, "sn ='".$edit_data["server_sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			echo '0';
		}
	}
	
	
	public function updateMailbox()
	{	
		foreach( $_POST as $key => $value )
		{
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$arr_data = array
		(   
			
			  "type" => tryGetData("type",$edit_data,NULL)
			, "type_str" => tryGetData("type_str",$edit_data,NULL)
			, "no" => tryGetData("no",$edit_data,NULL)
			, "desc" => tryGetData("desc",$edit_data,NULL)
			, "booked" => tryGetData("booked",$edit_data)
			, "booker" => tryGetData("booker",$edit_data)			
			, "booker_id" => tryGetData("booker_id",$edit_data)	
			, "user_sn" => tryGetData("user_sn",$edit_data)
			, "user_app_id" => tryGetData("user_app_id",$edit_data)
			, "user_building_id" => tryGetData("user_building_id",$edit_data)
			, "user_id" => tryGetData("user_id",$edit_data)
			, "user_name" => tryGetData("user_name",$edit_data)
			, "received" => tryGetData("received",$edit_data)
			, "receive_user_name" => tryGetData("receive_user_name",$edit_data)
			, "receive_user_sn" => tryGetData("receive_user_sn",$edit_data)
			, "is_receive" => tryGetData("is_receive",$edit_data,0)
			, "updated" =>  date( "Y-m-d H:i:s" )
		);
		
		
		if($this->it_model->updateData( "mailbox" , $arr_data, "client_sn ='".$edit_data["sn"]."' and comm_id = '".tryGetData("comm_id",$edit_data)."' " ))
		{					
			echo '1';						
		}
		else 
		{
			$arr_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$arr_data["client_sn"] = tryGetData("sn",$edit_data);			
			$content_sn = $this->it_model->addData( "mailbox" , $arr_data );
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
	
	
	
	public function fileUpload()
	{		
		foreach( $_FILES as $key => $file )
		{
			if(isNotNull($file['name']))
			{
				$key_ary = explode("<#-#>",$key);
				if(count($key_ary)!=2)
				{
					continue;
				}
				$comm_id = $key_ary[0];
				$folder = $key_ary[1];			
				
				
				if (!is_dir(set_realpath("upload/".$comm_id)))
				{
					mkdir(set_realpath("upload/".$comm_id),0777);
				}
				//dprint(set_realpath("upload/".$comm_id));
				
				
				if (!is_dir(set_realpath("upload/".$comm_id."/".$folder)))
				{
					mkdir(set_realpath("upload/".$comm_id."/".$folder),0777);
				}
				
				//圖片處理 img_filename	
				$uploadedUrl = "/share/MD0_DATA/Web/commapi/upload/".$comm_id."/".$folder."/".$file['name'];				
				move_uploaded_file( $file['tmp_name'], $uploadedUrl);
				
			
				
			}		
		}	
		

		dprint($_FILES);
	}
	
	
	public function askFile()
	{
		$file_string = $this->input->post("file_string",TRUE);	
		$comm_id = $this->input->post("comm_id",TRUE);		
		$folder = $this->input->post("folder",TRUE);	
		
		$upload_file_list = "";
		
		if(isNotNull($comm_id) && isNotNull($folder) )
		{
			if (is_dir(set_realpath("upload/".$comm_id."/".$folder)))
			{
				$client_file_ary = explode(",",$file_string);
				
				
				$server_folder = set_realpath("upload/".$comm_id."/".$folder);
				$files = glob($server_folder . '*');
				$server_file_ary = array();
				foreach( $files as $key => $file_name_with_full_path )
				{
					//echo '<br>'.basename($file_name_with_full_path);
					array_push($server_file_ary,basename($file_name_with_full_path));
				}
				
				//需要上傳的檔案
				//----------------------------------------------------------------
				$upload_file_ary = array_diff($client_file_ary,$server_file_ary);				
				$upload_file_list = implode(",",$upload_file_ary);
				//----------------------------------------------------------------
				
				//server 上需要刪除的檔案
				//----------------------------------------------------------------
				$del_file_ary = array_diff($server_file_ary,$client_file_ary);
				foreach( $del_file_ary as $key => $del_file )
				{					
					@unlink(set_realpath("upload/".$comm_id."/".$folder).$del_file);	
				}
				//----------------------------------------------------------------
			}
			else
			{
				$upload_file_list = $file_string;
			}
			
		}
		
		echo $upload_file_list;
	}
	
	
	
	
}
