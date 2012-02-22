<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Initial_permissions extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		$data = array(
			'name'        => 'Site.Antispam.Manage' ,
			'description' => 'Manage Antispam Options'
		);
		$this->db->insert("{$prefix}permissions", $data);
		
		$permission_id = $this->db->insert_id();
		
		// change the roles which don't have any specific login_destination set
		$this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1, ".$permission_id.")");

        $default_settings = "
			INSERT INTO `{$prefix}settings` (`name`, `module`, `value`) VALUES
			 ('antispam.antispam_enabled', 'antispam', '1'),
			 ('antispam.antispam_type', 'antispam', '1'),
			 ('antispam.recaptcha_key_public', 'antispam', ''),
			 ('antispam.recaptcha_key_private', 'antispam', ''),
			 ('antispam.recaptcha_theme', 'antispam', 'red'),
			 ('antispam.recaptcha_lang', 'antispam', 'en'),
			 ('antispam.recaptcha_compliant', 'antispam', '1');
		";
        $this->db->query($default_settings);
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		$query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = 'Site.Antispam.Manage'");
		foreach ($query->result_array() as $row)
		{
			$permission_id = $row['permission_id'];
			$this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
		}
		//delete the role
		$this->db->query("DELETE FROM {$prefix}permissions WHERE (name = 'Site.Antispam.Manage')");
        $this->db->query("DELETE FROM {$prefix}settings WHERE (module = 'antispam')");

	}
	
	//--------------------------------------------------------------------
	
}