<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Install_antispam extends Migration {

    private $permission_array = array(
        'Antispam.Settings.View' => 'To view the Antispam menu.',
        'Antispam.Settings.Manage' => 'Manage Antispam settings.',
    );

    public function up()
	{
		$prefix = $this->db->dbprefix;

        foreach ($this->permission_array as $name => $description)
        {
            $this->db->query("INSERT INTO {$prefix}permissions(name, description) VALUES('".$name."', '".$description."')");
            // give current role (or administrators if fresh install) full right to manage permissions
            $this->db->query("INSERT INTO {$prefix}role_permissions VALUES(1,".$this->db->insert_id().")");
        }
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

        //delete the permissions
        foreach ($this->permission_array as $name => $description)
        {
            $query = $this->db->query("SELECT permission_id FROM {$prefix}permissions WHERE name = '".$name."'");
            foreach ($query->result_array() as $row)
            {
                $permission_id = $row['permission_id'];
                $this->db->query("DELETE FROM {$prefix}role_permissions WHERE permission_id='$permission_id';");
            }
            //delete the role
            $this->db->query("DELETE FROM {$prefix}permissions WHERE (name = '".$name."')");
        }
        $this->db->query("DELETE FROM {$prefix}settings WHERE (module = 'antispam')");

	}
	
	//--------------------------------------------------------------------
	
}