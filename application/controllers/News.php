<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
class News extends REST_Controller {

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
                'message' => '操作錯誤'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code

        } else {


			$result = $this->c_model->GetList('news');
			$news = $result['data'];


			// If the sn parameter doesn't exist return all the news
			if ( isNull($sn) ) {
				// Check if the news data store contains news (in case the database result returns NULL)
				if ($news)
				{
					// Set the response and exit
					$this->response($news, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

				} else {

					// Set the response and exit
					$this->response([
						'status' => FALSE,
						'message' => '找不到任何資訊'
					], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
				}
			}

			// Find and return a single record for a particular user.

			$sn = (int) $sn;

			// Validate the sn.
			if ($sn <= 0) {
				// Invalid sn, set the response and exit.
				$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
			}

			// Get the user from the array, using the sn as key for retreival.
			// Usually a model is to be used for this.
			$new = NULL;

			if (!empty($news)) {
				foreach ($news as $key => $value) {
					if (isset($value['sn']) && $value['sn'] == $sn) {
						$new = $value;
					}
				}
			}

			if (!empty($new)) {
				$this->set_response($new, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
			
			} else {
				$this->set_response([
					'status' => FALSE,
					'message' => '您指定的訊息無法顯示 #'.$sn
				], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
			}
		}
    }

    public function index_post()
    {
        // $this->some_model->update_user( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'comm_id' => $this->post('comm_id'),
            'message' => 'Added a resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }


    public function index_delete($comm_id, $sn)
    {
		$comm_id = tryGetData('comm_id', $_POST, NULL);
		$sn = tryGetData('sn', $_POST, NULL);

        // Validate the id.
        if ( isNull($comm_id) && isNULL($sn) )
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'sn' => $sn,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
