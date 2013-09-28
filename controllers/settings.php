<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends Admin_Controller {

	//--------------------------------------------------------------------
	
	public function __construct() 
	{
		parent::__construct();
		

        $this->auth->restrict('Antispam.Settings.View');
        if (!class_exists('Activity_model'))
        {
            $this->load->model('activities/Activity_model', 'activity_model', true);
        }

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
         if ($this->input->post('submit'))
        {
            $this->auth->restrict('Antispam.Settings.Manage');
            if ($this->save_settings())
            {
                Template::set_message('Antispam settings were successfully saved.', 'success');
                redirect(SITE_AREA .'/settings/antispam');
            } else
            {
                Template::set_message('There was an error saving your antispam settings.', 'error');
            }
        }
        // Read our current settings
        $this->load->helper('recaptcha');

        $settings = $this->settings_lib->find_all_by('module','antispam');
        Template::set('settings', $settings);

        Template::set('toolbar_title', lang('us_antispam_title'));
        Template::set_view('antispam/settings/index');
        Template::render();
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------

    private function save_settings()
    {
		$this->form_validation->set_rules('antispam_enabled', lang('us_antispam_spambot'), 'trim|xss_clean');
        //$this->form_validation->set_rules('antispam_type', lang('us_antispam_service'), 'required|number|trim|xss_clean');
        $this->form_validation->set_rules('recaptcha_key_public', lang('us_recapcha_public'), 'trim|xss_clean');
        $this->form_validation->set_rules('recaptcha_key_private', lang('us_recapcha_private'), 'trim|xss_clean');
        $this->form_validation->set_rules('recaptcha_theme', lang('us_recapcha_theme'), 'trim|xss_clean');
        $this->form_validation->set_rules('recaptcha_lang', lang('us_recapcha_lang'), 'trim|xss_clean');
        $this->form_validation->set_rules('recaptcha_compliant', lang('us_recapcha_xhtml'), 'trim|xss_clean');

        if ($this->form_validation->run() === false)
        {
            return false;
        }

        $data = array(
            array('name' => 'antispam.antispam_enabled', 'value' => $this->input->post('antispam_enabled')),
            //array('name' => 'antispam.antispam_type', 'value' => ($this->input->post('antispam_type')) ? 1 : -1),
            array('name' => 'antispam.recaptcha_key_public', 'value' => $this->input->post('recaptcha_key_public')),
            array('name' => 'antispam.recaptcha_key_private', 'value' => $this->input->post('recaptcha_key_private')),
            array('name' => 'antispam.recaptcha_theme', 'value' => $this->input->post('recaptcha_theme')),
            array('name' => 'antispam.recaptcha_lang', 'value' => $this->input->post('recaptcha_lang')),
            array('name' => 'antispam.recaptcha_compliant', 'value' => $this->input->post('recaptcha_compliant')),
		);

        //destroy the saved update message in case they changed update preferences.
        /*if ($this->cache->get('update_message'))
        {
            if (!is_writeable(FCPATH.APPPATH.'cache/'))
            {
                $this->cache->delete('update_message');
            }
        }*/

        // Log the activity
        $this->activity_model->log_activity($this->auth->user_id(), lang('as_settings_saved').': ' . $this->input->ip_address(), 'antispam');

        // save the settings to the DB
        $updated = $this->settings_model->update_batch($data, 'name');

        return $updated;
	}
	
}

// End User Admin class