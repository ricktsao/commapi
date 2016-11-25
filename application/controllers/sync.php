<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync extends CI_Controller {


	
	
	function __construct() 
	{
		parent::__construct();	  
		$this->load->database();

		
		// 取得戶別相關參數
		$this->load->model('auth_model');
		$this->building_part_01 = $this->auth_model->getWebSetting('building_part_01');
		$building_part_01_value = $this->auth_model->getWebSetting('building_part_01_value');
		$this->building_part_02 = $this->auth_model->getWebSetting('building_part_02');
		$building_part_02_value = $this->auth_model->getWebSetting('building_part_02_value');
		$this->building_part_03 = $this->auth_model->getWebSetting('building_part_03');

		if (isNotNull($building_part_01_value)) {
			$this->building_part_01_array = array_merge(array(0=>' -- '), explode(',', $building_part_01_value));
		}

		if (isNotNull($building_part_02_value)) {
			$this->building_part_02_array = array_merge(array(0=>' -- '), explode(',', $building_part_02_value));
		}
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
			, "post_date" => tryGetData("post_date", $edit_data)		
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
					// 租售屋照片放在各則物件序號下
					if (mb_substr($folder, 0, 9) == 'house_to_') {

						$folder_level = explode("/", $folder);
//dprint($folder_level);

						if (!is_dir(set_realpath("upload/".$comm_id."/".$folder_level[0])))
						{
//dprint(set_realpath("upload/".$comm_id."/".$folder_level[0]));
							mkdir(set_realpath("upload/".$comm_id."/".$folder_level[0]),0777);
						}
						
						if (!is_dir(set_realpath("upload/".$comm_id."/".$folder_level[0]."/".$folder_level[1])))
						{
//@dprint(set_realpath("upload/".$comm_id."/".$folder_level[0]."/".$folder_level[1]));

							mkdir(set_realpath("upload/".$comm_id."/".$folder_level[0]."/".$folder_level[1]),0777);
						}

					} else {

						mkdir(set_realpath("upload/".$comm_id."/".$folder),0777);
					}

				}
				
				//圖片處理 img_filename	
				$uploadedUrl = "/share/MD0_DATA/Web/commapi/upload/".$comm_id."/".$folder.$file['name'];
//$uploadedUrl = "C:/wamp2/www/commapi/upload/".$comm_id."/".$folder.$file['name'];

				$moved = move_uploaded_file( $file['tmp_name'], $uploadedUrl);
				
				if( $moved ) {
				  echo "Successfully uploaded ";
				  echo $file['tmp_name'];
				  echo $uploadedUrl;
				} else {
				  echo "Not uploaded because of error #";dprint($_FILES);
				}

				
			}		
		}	

		//dprint($_FILES);
	}
	
	
	public function here()
	{
	dprint('here');
	}

	public function askFile()
	{
		$file_string = $this->input->post("file_string",TRUE);	
		$comm_id = $this->input->post("comm_id",TRUE);		
		$folder = $this->input->post("folder",TRUE);	
		
		$upload_file_list = "";
dprint('askFile = = = ');
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

dprint($upload_file_list);
dprint($server_folder);
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
	




	public function updateUser()
	{	
		
		$edit_data = array();
		foreach( $_POST as $key => $value )
		{
			if (mb_substr($key,0,4) == 'app_' and $key!=='app_id') {
				continue;
			} elseif (in_array($key, array('account', 'password', 'is_sync', 'owner_addr')) ) {
				continue;
			}
			
			if ( $key == 'name' ) {
				$name = $this->input->post($key,TRUE);
				$name = mb_substr($name, 0, 1);
				$edit_data[$key] = $name;

			} else {
				$edit_data[$key] = $this->input->post($key,TRUE);
			}
		}

		$edit_data['building_text'] = building_id_to_text($edit_data['building_id']);


		if ($edit_data['gender'] == 2) {
			$edit_data['name'] .= '小姐';
		} else {
			$edit_data['name'] .= '先生';
		}

		$edit_data['updated'] = date('Y-m-d H:i:s');

		$client_sn = $edit_data["sn"];
		unset($edit_data["sn"]);

		if($this->it_model->updateData( "sys_user" , $edit_data, "client_sn ='".$client_sn."' AND comm_id = '".tryGetData("comm_id", $edit_data)."' " ))
		{
			echo '1';

		} else  {

			$edit_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$edit_data["client_sn"] = $client_sn;			
			$content_sn = $this->it_model->addData( "sys_user" , $edit_data );

			if($content_sn > 0) {		
				echo '1';		
			} else {
				echo '0';	
			}		
		}	
		
	}


	public function updateRentHouse()
	{	
		
		$edit_data = array();
		foreach( $_POST as $key => $value )
		{
			if ($key=='is_sync') {
				continue;
			}
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	

		$client_sn = $edit_data["sn"];
		unset($edit_data["sn"]);

		if($this->it_model->updateData( "house_to_rent" , $edit_data, "client_sn ='".$client_sn."' and comm_id = '".tryGetData("comm_id", $edit_data)."' " ))
		{					
			echo '1';

		} else  {

			$edit_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$edit_data["client_sn"] = $client_sn;			
			$content_sn = $this->it_model->addData( "house_to_rent" , $edit_data );

			if($content_sn > 0) {		
				echo '1';		
			} else {
				echo '0';	
			}		
		}	
		
	}



	public function updateSaleHouse()
	{	
		
		$edit_data = array();
		foreach( $_POST as $key => $value )
		{
			if ($key=='is_sync') {
				continue;
			}
			$edit_data[$key] = $this->input->post($key,TRUE);			
		}	
		
		$client_sn = $edit_data["sn"];
		unset($edit_data["sn"]);

		if($this->it_model->updateData( "house_to_sale" , $edit_data, "client_sn ='".$client_sn."' and comm_id = '".tryGetData("comm_id", $edit_data)."' " ))
		{					
			echo '1';

		} else  {

			$edit_data["comm_id"] = tryGetData("comm_id",$edit_data);
			$edit_data["client_sn"] = $client_sn;			
			$content_sn = $this->it_model->addData( "house_to_sale" , $edit_data );

			if($content_sn > 0) {		
				echo '1';		
			} else {
				echo '0';	
			}		
		}	
		
	}


	public function updateRentHousePhoto()
	{	
		
		$edit_data = array();
		foreach( $_POST as $key => $value )
		{
			if ($key=='is_sync') {
				continue;
			}
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

//dprint('sync  updateSaleHousePhoto +++');
//dprint($edit_data);
		$client_house_to_rent_sn = $edit_data["house_to_rent_sn"];
		unset($edit_data['sn']);

		if($this->it_model->updateData( "house_to_rent_photo" 
										, $edit_data
										, "client_sn ='".$client_house_to_rent_sn."' and filename ='".tryGetData("filename", $edit_data)."' and comm_id = '".tryGetData("comm_id", $edit_data)."' " ) )
		{
			echo '1';

		} else  {

			$edit_data["comm_id"] = tryGetData("comm_id", $edit_data);
			$edit_data["client_sn"] = $client_house_to_rent_sn;
			$content_sn = $this->it_model->addData( "house_to_rent_photo" , $edit_data );

			if($content_sn > 0) {		
				echo '1';		
			} else {
				echo '0';	
			}		
		}
		//dprint('### updateRentHousePhoto :: '.$this->db->last_query());
		
	}


	public function updateSaleHousePhoto()
	{	
		
		$edit_data = array();
		foreach( $_POST as $key => $value )
		{
			if ($key=='is_sync') {
				continue;
			}
			$edit_data[$key] = $this->input->post($key,TRUE);
		}

//dprint('sync  updateSaleHousePhoto +++');
//dprint($edit_data);
		$client_house_to_sale_sn = $edit_data["house_to_sale_sn"];
		unset($edit_data['sn']);

		if($this->it_model->updateData( "house_to_sale_photo" 
										, $edit_data
										, "client_sn ='".$client_house_to_sale_sn."' and filename ='".tryGetData("filename", $edit_data)."' and comm_id = '".tryGetData("comm_id", $edit_data)."' " ) )
		{
			echo '1';

		} else  {

			$edit_data["comm_id"] = tryGetData("comm_id", $edit_data);
			$edit_data["client_sn"] = $client_house_to_sale_sn;
			$content_sn = $this->it_model->addData( "house_to_sale_photo" , $edit_data );

			if($content_sn > 0) {		
				echo '1';		
			} else {
				echo '0';	
			}		
		}	
		
	}






	
	/**
	 * 查詢由app user登入狀況
	**/
	public function getAppUser()
	{
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$condition = "comm_id = '".$comm_id."' and role ='I'";			
		$user_list = $this->it_model->listData( "sys_user" , $condition );
				
		echo json_encode($user_list["data"]);
	}
	
}
