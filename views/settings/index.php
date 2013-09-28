<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>


<div class="admin-box">

    <h3>General Settings</h3>
    <?php echo form_open($this->uri->uri_string(), 'class="form-horizontal"'); ?>
    <fieldset>

        <!-- Eneable/Disable antispam -->
        <div class="control-group <?php echo form_error('antispam_enabled') ? 'error' : '' ?>">
             <label class="control-label"><?php echo lang('us_antispam_spambot') ?></label>
            <div class="controls">
                <?php
                $use_selection = ((isset($settings['antispam.antispam_enabled']) && $settings['antispam.antispam_enabled'] == 1) || !isset($settings['antispam.antispam_enabled'])) ? true : false;
                echo form_checkbox('antispam_enabled',1, $use_selection,'id="antispam_enabled"');
                ?>
                <?php if (form_error('antispam_enabled')) echo '<span class="help-inline">'. form_error('antispam_enabled') .'</span>'; ?>
            </div>
        </div>

    <div id="ootp_block">

        <legend>reCAPTCHA</legend>

		<?php echo lang('us_recapcha_note'); ?><p /><br />

            <!-- reCAPTCHA AUTH -->
		<fieldset>
            <legend><?php echo lang('us_recapcha_auth'); ?></legend>
            <div>
            <?php echo lang('us_recapcha_auth_note'); ?>
            </div>

            <div class="control-group <?php echo form_error('recaptcha_key_public') ? 'error' : '' ?>">
                 <label class="control-label"><?php echo lang('us_recapcha_public') ?></label>
                <div class="controls">
                    <input type="text" id="recaptcha_key_public" name="recaptcha_key_public" value="<?php echo (isset($settings['antispam.recaptcha_key_public'])) ? $settings['antispam.recaptcha_key_public']: set_value('antispam.recaptcha_key_public'); ?>" />
                    <?php if (form_error('recaptcha_key_public')) echo '<span class="help-inline">'. form_error('recaptcha_key_public') .'</span>'; ?>
                </div>
            </div>

            <div class="control-group <?php echo form_error('recaptcha_key_private') ? 'error' : '' ?>">
                 <label class="control-label"><?php echo lang('us_recapcha_private') ?></label>
                <div class="controls">
                    <input type="text" class="small" id="recaptcha_key_private" name="recaptcha_key_private" value="<?php echo (isset($settings['antispam.recaptcha_key_private'])) ? $settings['antispam.recaptcha_key_private']: set_value('antispam.recaptcha_key_private'); ?>" />
                    <?php if (form_error('recaptcha_key_private')) echo '<span class="help-inline">'. form_error('recaptcha_key_private') .'</span>'; ?>
                </div>
            </div>
        </fieldset>

		<fieldset>
		    <legend><?php echo lang('us_recapcha_opt'); ?></legend>
                <!-- THRME -->
            <div class="control-group <?php echo form_error('recaptcha_theme') ? 'error' : '' ?>">
                 <label class="control-label"><?php echo lang('us_recapcha_theme') ?></label>
                <div class="controls">
                    <select id="recaptcha_theme" name="recaptcha_theme" class="small" >
                        <?php
                        $themes = loadRecaptchaThemes();
                        foreach($themes as $id => $theme){
                            echo('<option value="'.$id.'"');
                            if (isset($settings['antispam.recaptcha_theme']) && $settings['antispam.recaptcha_theme'] == $id) {
                                echo(' selected="selected"');
                            }
                            echo('">'.$theme.'</option>');
                        }
                        ?>
                    </select><?php if (form_error('recaptcha_theme')) echo '<span class="help-inline">'. form_error('recaptcha_theme') .'</span>'; ?>
                </div>
            </div>

                <!-- Language -->
            <div class="control-group <?php echo form_error('recaptcha_lang') ? 'error' : '' ?>">
                 <label class="control-label"><?php echo lang('us_recapcha_lang') ?></label>
                <div class="controls">
                    <select id="recaptcha_lang" name="recaptcha_lang" class="small" >
                        <?php
                        $langs = loadRecaptchaLangs();
                        foreach($langs as $id => $lang){
                            echo('<option value="'.$id.'"');
                            if (isset($settings['antispam.recaptcha_lang']) && $settings['antispam.recaptcha_lang'] == $id) {
                                echo(' selected="selected"');
                            }
                            echo('">'.$lang.'</option>');
                        }
                        ?>
                    </select>
                    <?php if (form_error('recaptcha_lang')) echo '<span class="help-inline">'. form_error('recaptcha_lang') .'</span>'; ?>
                </div>
            </div>

                <!-- AUTO LOAD -->
            <div class="control-group <?php echo form_error('recaptcha_compliant') ? 'error' : '' ?>">
                 <label class="control-label"><?php echo lang('us_recapcha_stnd') ?></label>
                <div class="controls">
                    <?php
                    $compliant = (isset($settings['antispam.recaptcha_compliant']) && $settings['antispam.recaptcha_compliant'] == 1) ? true : false;
                    echo form_radio('recaptcha_compliant',1, $compliant);
                    echo(lang('us_recapcha_xhtml')."<br />");
                    ?>
                    <label>&nbsp;</label>
                    <?php
                    $compliant = (isset($settings['recaptcha_compliant']) && $settings['recaptcha_compliant'] == -1) ? true : false;
                    echo form_radio('recaptcha_compliant',-1, $compliant);
                    echo(lang('us_recapcha_none'));
                    ?>
                    <?php if (form_error('recaptcha_compliant')) echo '<span class="help-inline">'. form_error('recaptcha_compliant') .'</span>'; ?>
                </div>
            </div>
    </div>
    </fieldset>

	<div class="form-actions">
		<input type="submit" name="submit" class="btn btn-primary" value="<?php echo lang('bf_action_save') .' '. lang('bf_context_settings') ?>" />
	</div>

</div>
	
<?php echo form_close(); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('input#antispam_enabled').click(function() {
			$('#ootp_block').toggle(this.checked);
		});
		$('#ootp_block').toggle($('input#antispam_enabled').is(':checked'));
	});
</script>

