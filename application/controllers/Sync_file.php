<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync_file extends CI_Controller
{

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
						/*
						if (!is_dir(set_realpath("upload/".$comm_id."/".$folder_level[0])))
						{
//dprint(set_realpath("upload/".$comm_id."/".$folder_level[0]));
							mkdir(set_realpath("upload/".$comm_id."/".$folder_level[0]),0777,true);
						}
						*/
						if (!is_dir(set_realpath("upload/".$comm_id."/".$folder_level[0]."/".$folder_level[1])))
						{
//@dprint(set_realpath("upload/".$comm_id."/".$folder_level[0]."/".$folder_level[1]));

							mkdir(set_realpath("upload/".$comm_id."/".$folder_level[0]."/".$folder_level[1]),0777,true);
						}

					} else {

						mkdir(set_realpath("upload/".$comm_id."/".$folder),0777 ,true );
					}

				}
				
				//圖片處理 img_filename	
				//$uploadedUrl = "/share/MD0_DATA/Web/commapi/upload/".$comm_id."/".$folder.$file['name'];
				$uploadedUrl = "/home/edoma/public_html/commapi/upload/".$comm_id."/".$folder.$file['name'];
//$uploadedUrl = "C:/wamp2/www/commapi/upload/".$comm_id."/".$folder.$file['name'];

				$moved = move_uploaded_file( $file['tmp_name'], $uploadedUrl);

				
				if( $moved ) {
				  //echo "Successfully uploaded ";
				  //echo $file['tmp_name'];
				  //echo $uploadedUrl;
				} else {
				  echo "Not uploaded because of error #";dprint($_FILES);
				}
				

				
			}		
		}	

		//dprint($_FILES);
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
		$client_sn = $edit_data["sn"];
		unset($edit_data['sn']);

		if($this->it_model->updateData( "house_to_rent_photo" 
										, $edit_data
										, "client_sn ='".$client_sn."' and comm_id = '".tryGetData("comm_id", $edit_data)."' " ) )
		{
			echo '1';

		} else  {

			$edit_data["comm_id"] = tryGetData("comm_id", $edit_data);
			$edit_data["client_sn"] = $client_sn;
			$content_sn = $this->it_model->addData( "house_to_rent_photo" , $edit_data );

			if($content_sn > 0) {		
				echo '1';		
			} else {
				echo '0';	
			}		
		}	
		
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
		$client_sn = $edit_data["sn"];
		unset($edit_data['sn']);

		if($this->it_model->updateData( "house_to_sale_photo" 
										, $edit_data
										, "client_sn ='".$client_sn."' and comm_id = '".tryGetData("comm_id", $edit_data)."' " ) )
		{
			echo '1';

		} else  {

			$edit_data["comm_id"] = tryGetData("comm_id", $edit_data);
			$edit_data["client_sn"] = $client_sn;
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
