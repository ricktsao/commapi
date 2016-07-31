<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
//require APPPATH . '/libraries/REST_Controller.php';

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
class Sale_House extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

		$this->load->database();

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

        if ( isNull($comm_id) ) {

            $this->set_response([
                'status' => FALSE,
                'message' => '請指定社區'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

        } else {

			$condition = 'comm_id="'.$comm_id.'" AND '.$this->it_model->getEffectedSQL('house_to_sale');
			
			if ( isNotNull($sn) ) {
				// If the sn parameter doesn't exist return all the rents
				$condition .= ' AND sn = '.$sn;
			}

			$result = $this->it_model->listData('house_to_sale', $condition);
			

			// Check if the rents data store contains rents (in case the database result returns NULL)
			if ($result['count'] > 0) {

				//$rents = $result['data'];
				//$config_electric_array = config_item('electric_array');
				//$config_furniture_array = config_item('furniture_array');
				$config_gender_array2 = config_item('gender_array2');
				$config_parking_array = config_item('parking_array');
				$config_rent_sale_type_array = config_item('rent_type_array');
				$config_house_type_array = config_item('house_type_array');
				$config_house_direction_array = config_item('house_direction_array');

				foreach ($result['data'] as $item) {

					//$gender_term = $item['gender_term'];
					//$item['gender_term'] = $config_gender_array2[$gender_term];

					// 可否開伙
					$flag_cooking = tryGetData('flag_cooking', $item, NULL);
					if ( isNotNull($flag_cooking) ) {

						if ($flag_rent != 1) {
							$item['flag_rent'] = "無";

						} else {
							$item['flag_rent'] = "有";
						}
					}


					// 車位
					$flag_parking = tryGetData('flag_parking', $item, NULL);
					$item['flag_parking'] = tryGetData($flag_parking, $config_parking_array, NULL);

					// 型態
					$rent_type = tryGetData('rent_type', $item, NULL);
					$item['rent_type'] = tryGetData($rent_type, $config_rent_sale_type_array, NULL);

					// 物件類型
					$house_type = tryGetData('house_type', $item, NULL);
					$item['house_type'] = tryGetData($rent_type, $config_house_type_array, NULL);

					// 物件類型
					$direction = tryGetData('direction', $item, NULL);
					$item['direction'] = tryGetData($direction, $config_house_direction_array, NULL);

					/* 家具
					$given_furni_ary = explode(',' , $item['furniture']);
					$furni_ary = array();
					foreach($config_furniture_array as $furni) {

						if ( in_array($furni['value'], $given_furni_ary) ) {
							$furni_ary[] = $furni['title'];
						}
					}
					$item['furniture'] = implode(',', $furni_ary);

					// 家電
					$given_ele_ary = explode(',' , $item['electric']);
					$ele_ary = array();
					foreach($config_electric_array as $ele) {

						if ( in_array($ele['value'], $given_ele_ary) ) {
							$ele_ary[] = $ele['title'];
						}
					}
					$item['electric'] = implode(',', $ele_ary);
					*/

					// 照片
					$condition = 'del=0 AND house_to_sale_sn='.$item['sn'];
					$phoresult = $this->it_model->listData('house_to_sale_photo', $condition);
					$photos = array();
					foreach ($phoresult['data'] as $photo) {
						$img = $this->config->item("api_server_url").'upload/'.$comm_id.'/house_to_sale/'.$item['sn'].'/'.$photo['filename'];
						$photos[] = array('photo' => $img
										, 'title' => $photo['title'] );
					}
					$item['photos'] = $photos;
					$rents[] = $item;
				}
				// Set the response and exit
				$this->response($rents, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

			} else {

				// Set the response and exit
				$this->response([
					'status' => FALSE,
					'message' => '找不到任何售屋資訊，請確認'
				], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    public function index_post($comm_id)
    {
        // $this->some_model->update_rent( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'comm_id' => $comm_id,
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }



}
