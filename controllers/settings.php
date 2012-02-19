<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		
		$this->auth->restrict('Site.Antispam.Manage');
		//$this->auth->restrict('OOTP.Manager.View');
		
		$this->lang->load('antispam');

	}
	
	//--------------------------------------------------------------------

	public function _remap($method) 
	{ 
		if (method_exists($this, $method))
		{
			$this->$method();
		}
	}
	
	//--------------------------------------------------------------------
	public function index()
	{
		$this->load->helper('recaptcha');
		$this->load->library('form_validation');

		if ($this->input->post('submit'))
		{
            $this->form_validation->set_rules('antispam_enabled', lang('us_antispam_spambot'), 'trim|xss_clean');
            //$this->form_validation->set_rules('antispam_type', lang('us_antispam_service'), 'required|number|trim|xss_clean');
			$this->form_validation->set_rules('recaptcha_key_public', lang('us_recapcha_public'), 'trim|xss_clean');
            $this->form_validation->set_rules('recaptcha_key_private', lang('us_recapcha_private'), 'trim|xss_clean');
			$this->form_validation->set_rules('recaptcha_theme', lang('us_recapcha_theme'), 'trim|xss_clean');
			$this->form_validation->set_rules('recaptcha_lang', lang('us_recapcha_lang'), 'trim|xss_clean');
			$this->form_validation->set_rules('recaptcha_compliant', lang('us_recapcha_xhtml'), 'trim|xss_clean');
			
			if ($this->form_validation->run() !== FALSE)
			{
				$data = array(
					'antispam_enabled'			=> $this->input->post('antispam_enabled'),
					//'antispam_type'			=> ($this->input->post('antispam_type')) ? 1 : -1,
					'recaptcha_key_public'		=> $this->input->post('recaptcha_key_public'),
					'recaptcha_key_private'		=> $this->input->post('recaptcha_key_private'),
					'recaptcha_theme'			=> $this->input->post('recaptcha_theme'),
					'recaptcha_lang'			=> $this->input->post('recaptcha_lang'),
					'recaptcha_compliant'		=> $this->input->post('recaptcha_compliant'),
					
				);

				if (write_config('antispam', $data)) {
					// Success, so reload the page, so they can see their settings
					Template::set_message('Antispam settings successfully saved.', 'success');
				}
				else
				{
					Template::set_message('There was an error saving the file: config/antispam.', 'error');
				}
			}
		}

		// Load our current settings
		Template::set(read_config('antispam'));

		Template::set('toolbar_title', lang('us_antispam_title'));
        Template::set_view('antispam/settings/index');
		Template::render();
	}
	
}

// End User Admin class