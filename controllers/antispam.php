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
	* 	@param	#resp	{String}	Response String
	* 	@return			{JSON}		JSON Response String
	*
	* 	@access			public
	*/
	public function captchaTest() {
		
		$sec_configs = read_config('security');
		$code = 200;
		$status = $answer = 'OK';
		$priv_key = (isset($sec_configs['recaptcha_key_private'])) ? $sec_configs['recaptcha_key_private'] : '';
		if (!empty($priv_key)) {
			if (isset($this->uriVars['chlg']) && !empty($this->uriVars['chlg']) &&
			isset($this->uriVars['resp']) && !empty($this->uriVars['resp'])) {
				$this->load->helper('security/helpers/recaptcha');
				$resp = recaptcha_check_answer($priv_key, $_SERVER['REMOTE_ADDR'],
				$this->uriVars['chlg'],
				$this->uriVars['resp']);
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