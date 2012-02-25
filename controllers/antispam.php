<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Antispam extends Front_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
	}

	//--------------------------------------------------------------------
	
	public function index()
	{
		Template::render();
	}
	
	/*------------------------------------------------------
	/
	/	AJAX FUNCTIONS
	/
	/-----------------------------------------------------*/
	/**
	* 	RECAPTCHA TEST
	* 	TEST UPER RECAPPTCHA DATA AGAINST THE EXPECTED VALUE.
	* 	This function is used sitewide by all reCAPTCHA submissions.
	*
	* 	URI VARS DATA PARAMS
	* 	@param	$chlg	{String}	Challenge String
	* 	@param	#res	{String}	Response String
	* 	@return			{JSON}		JSON Response String
	*
	* 	@access			public
	*/
	public function captchaTest() {
		
		$settings = $this->settings_lib->find_all_by('module','antispam');
        
		$clg = $this->uri->segment(3);
		$res = $this->uri->segment(4);
		$code = 200;
		$status = $answer = 'OK';
		$priv_key = (isset($settings['antispam.recaptcha_key_private'])) ? $settings['antispam.recaptcha_key_private'] : '';
		if (!empty($priv_key)) {
			if (isset($clg) && !empty($clg) && isset($res) && !empty($res)) {
				$this->load->helper('antispam/helpers/recaptcha');
				$resp = recaptcha_check_answer($priv_key, $_SERVER['REMOTE_ADDR'],$clg,$res);
				if (!$resp->is_valid) {
					$code = 301;
					$status = "fail";
					$answer = "reCAPTCHA verification failed. please try again.";
				}
			} else {
				$code = 301;
				$status = "fail";
				$answer = "Required reCAPTCHA parameters were missing.";
			} // END if
		} else {
			$code = 301;
			$status = "fail";
			$answer = "Required reCAPTCHA credentials for this site are missing.";
		} // END if
		$result = '{"result":"'.$answer.'","code":"'.$code.'","status":"'.$status.'"}';
		$this->output->set_header('Content-type: application/json');
		$this->output->set_output($result);
	}
}

// End User League_Manager class