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
class User extends REST_Controller {

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
		/* http://localhost/commapi/user/index/?comm_id=5tgb4rfv&id=1234567891&app_id=666777888 */

		$comm_id = tryGetData('comm_id', $_GET, NULL);
		$id = tryGetData('id', $_GET, NULL);
		$app_id = tryGetData('app_id', $_GET, NULL);

        if ( isNull($comm_id) && isNull($id) && isNull($app_id) ) {

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        } else {

			$query = 'SELECT SQL_CALC_FOUND_ROWS comm_id, id, app_id, name, gender, building_id, voting_right, gas_right '
					.'  FROM sys_user '
					.' WHERE role = "I" '
					.'   AND comm_id="'.$comm_id.'" '
					.'   AND '.'id="'.$id.'" '
					.'   AND '.'app_id="'.$app_id.'" '	//.$this->it_model->getEffectedSQL('rent_house');
					;
			$result = $this->it_model->runSql($query);
			//echo $result['sql'];

			// Check if the rents data store contains rents (in case the database result returns NULL)
			if ($result['count'] > 0) {

				$user = $result['data'][0];



				$arr_data = array('last_login_ip' => $_SERVER['REMOTE_ADDR']
								, 'last_login_time' => date('Y-m-d H:i:00')
								, 'last_login_agent' => '[APP] '.$_SERVER['HTTP_USER_AGENT']
								);

				$arr_return = $this->it_model->updateDB( "sys_user" 
														, $arr_data
														, 'role = "I" AND comm_id="'.$comm_id.'" AND id="'.$id.'" ' );

				// Set the response and exit
				$this->response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

			} else {

				// Set the response and exit
				$this->response([
					'status' => FALSE,
					'message' => '找不到任何住戶資訊，請確認'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

			}
		}
	}


    public function activate_post()
    {		
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$id = tryGetData('id', $_POST, NULL);
		$app_id = tryGetData('app_id', $_POST, NULL);

        if ( isNull($comm_id) && isNull($id) && isNull($app_id) ) {

            $this->set_response([
                'status' => FALSE,
                'message' => '缺少必要資料，請確認'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        } else {

			$query = 'SELECT SQL_CALC_FOUND_ROWS comm_id, id, app_id '
					.'  FROM sys_user '
					.' WHERE role = "I" '
					.'   AND comm_id="'.$comm_id.'" '
					.'   AND id="'.$id.'" '
					.'   AND app_id IS NULL '	//.$this->it_model->getEffectedSQL('rent_house');
					;
			$result = $this->it_model->runSql($query);
			// Check if the rents data store contains rents (in case the database result returns NULL)
			if ($result['count'] > 0) {

				$user = $result['data'][0];

				$arr_data = array('app_id' => $app_id
								, 'start_date' => date('Y-m-d H:i:00')
								, 'forever' => 1
								, 'updated' => date('Y-m-d H:i:00')
								);

				$arr_return = $this->it_model->updateDB( "sys_user" 
														, $arr_data
														, 'role = "I" AND comm_id="'.$comm_id.'" AND id="'.$id.'" ' );
				//dprint($this->db->last_query());
				if($arr_return['success'])
				{
					$this->set_response([
						'status' => FALSE,
						'message' => '您的APP已經開通'
					], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code		
				}
				else 
				{
					// Set the response and exit
					$this->response([
						'status' => FALSE,
						'message' => '找不到您的住戶資訊或者您已開通，請確認'
					], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
					}


			} else {

				// Set the response and exit
				$this->response([
					'status' => FALSE,
					'message' => '找不到您的住戶資訊或者您已開通，請確認!!'
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

			}
		}
	}



}
