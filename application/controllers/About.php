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
class About extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

		$this->load->database();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['index_get']['limit'] = 50000; // 500 requests per hour per user/key
        $this->methods['index_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['index_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    
	
	
	public function index_get()
    {
        
		$about_list = $this->it_model->listData( "edoma_content" , "content_type = 'about'" , NULL , NULL , array("sort"=>"asc","start_date"=>"desc","sn"=>"desc") );
		if ($about_list['count'] > 0) 
		{
			$about_info = $about_list["data"][0];
			$ajax_ary = array();
			$ajax_ary["content"] = $about_info["content"];
		
			$this->set_response($ajax_ary, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code	
		} 
		else 
		{

			// Set the response and exit
			$this->response([
				'status' => FALSE,
				'message' => '找不到任何資訊，請確認'
			], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code

		}
		
	} 

}
