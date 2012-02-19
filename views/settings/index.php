<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>

<p class="small"><?php echo lang('bf_required_note'); ?></p>

<?php echo form_open($this->uri->uri_string(), 'class="constrained ajax-form"'); ?>

		<!-- Eneable/Disable antispam -->
	<div>
		<label for="antispam_enabled"><?php echo lang('us_antispam_spambot'); ?></label>
		<?php
		$use_selection = ((isset($antispam_enabled) && $antispam_enabled == 1) || !isset($antispam_enabled)) ? true : false;
		echo form_checkbox('antispam_enabled',1, $use_selection,'id="antispam_enabled"');
		?>
	</div>
    		<!-- OOTP DETAILS OVERRIDE 
	<div>
		<label for="antispam_type"><?php //echo lang('us_antispam_service'); ?></label>
		<?php
		//$use_selection = ((isset($antispam_type) && $antispam_type == 1) || !isset($antispam_type)) ? true : false;
		//echo form_checkbox('antispam_type',1, $use_selection,'id="antispam_type"');
		?>
	</div>-->
    <div id="ootp_block">
		<div>&nbsp;</div>
        <div>
		<?php echo lang('us_recapcha_note'); ?>
		</div>
            <!-- reCAPTCHA AUTH -->
		<fieldset>
		<legend><?php echo lang('us_recapcha_auth'); ?></legend>
		</fieldset>
		<div>
		<?php echo lang('us_recapcha_auth_note'); ?>
		</div>
		<div>
            <label for="recaptcha_key_public"><?php echo lang('us_recapcha_public'); ?></label>
            <input type="text" id="recaptcha_key_public" name="recaptcha_key_public" value="<?php echo (isset($recaptcha_key_public)) ? $recaptcha_key_public: set_value('recaptcha_key_public'); ?>" />
        </div>
            <!-- LEAGUE ABBR -->
        <div>
            <label for="recaptcha_key_private"><?php echo lang('us_recapcha_private'); ?></label>
            <input type="text" class="small" id="recaptcha_key_private" name="recaptcha_key_private" value="<?php echo (isset($recaptcha_key_private)) ? $recaptcha_key_private: set_value('recaptcha_key_private'); ?>" />
        </div>
        
		<fieldset>
		<legend><?php echo lang('us_recapcha_opt'); ?></legend>
		</fieldset>
		<div>
			<label for="recaptcha_theme"><?php echo lang('us_recapcha_theme'); ?></label>
			<select id="recaptcha_theme" name="recaptcha_theme" class="small" >
			<?php
				$themes = loadRecaptchaThemes();
				foreach($themes as $id => $theme){
					echo('<option value="'.$id.'"');
					if (isset($recaptcha_theme) && $recaptcha_theme == $id) { 
						echo(' selected="selected"');
					}
					echo('">'.$theme.'</option>');
				} 
			?>
			</select>
		</div>
		<div>
			<label for="recaptcha_lang"><?php echo lang('us_recapcha_lang'); ?></label>
			<select id="recaptcha_lang" name="recaptcha_lang" class="small" >
			<?php
				$langs = loadRecaptchaLangs();
				foreach($langs as $id => $lang){
					echo('<option value="'.$id.'"');
					if (isset($recaptcha_lang) && $recaptcha_lang == $id) { 
						echo(' selected="selected"');
					}
					echo('">'.$lang.'</option>');
				} 
			?>
			</select>
		</div>
		
			<!-- AUTO LOAD -->
		<div>
			<label for="recaptcha_compliant"><?php echo lang('us_recapcha_stnd'); ?></label>
			<?php
			$compliant = (isset($recaptcha_compliant) && $recaptcha_compliant == 1) ? true : false;
			echo form_radio('recaptcha_compliant',1, $compliant);
			echo(lang('us_recapcha_xhtml')."<br />");
			?>
			<label for="recaptcha_compliant">&nbsp;</label>
			<?php
            $compliant = (isset($recaptcha_compliant) && $recaptcha_compliant == -1) ? true : false;
            echo form_radio('recaptcha_compliant',-1, $compliant);
			echo(lang('us_recapcha_none'));
			?>
		</div>
	
	<div class="submits">
		<input type="submit" name="submit" value="<?php echo lang('bf_action_save') ?> " /> <?php echo lang('bf_or') ?> <?php echo anchor(SITE_AREA .'/settings', lang('bf_action_cancel')); ?>
	</div>
	
<?php echo form_close(); ?>

<script type="text/javascript">
    head.ready(function(){
        $(document).ready(function() {
            $('input#antispam_enabled').click(function() {
                $('#ootp_block').toggle(this.checked);
            });
            $('#ootp_block').toggle($('input#antispam_enabled').is(':checked'));
        });
    });

</script>

