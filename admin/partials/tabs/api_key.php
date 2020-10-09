<input type="hidden" name="squalomail_active_settings_tab" value="api_key"/>

<!-- remove some meta and generators from the <head> -->
<fieldset class="full">
    <legend class="screen-reader-text">
        <span><?php esc_html_e('Connect your store to SqualoMail', 'squalomail-for-woocommerce');?></span>
    </legend>
    
    
    <a id="squalomail-oauth-connect" class="button button-primary tab-content-submit oauth-connect"><?php $has_valid_api_key ? esc_html_e('Reconnect', 'squalomail-for-woocommerce') : esc_html_e('Connect', 'squalomail-for-woocommerce');?></a>
    <h4><?php esc_html_e('Connect your store to SqualoMail', 'squalomail-for-woocommerce'); ?></h4>
    <input type="hidden" id="<?php echo $this->plugin_name; ?>-squalomail-api-key" name="<?php echo $this->plugin_name; ?>[squalomail_api_key]" value="<?php echo isset($options['squalomail_api_key']) ? $options['squalomail_api_key'] : '' ?>" required/>
    <?php if ($has_valid_api_key) :?>
        <p id="squalomail-oauth-api-key-valid"><?php esc_html_e('Already connected. You can reconnect with another SqualoMail account if you want.' , 'squalomail-for-woocommerce');?></p>
    <?php endif;?>
    <p id="squalomail-oauth-waiting" class="oauth-description"><?php esc_html_e('Connecting. A new window will open with SqualoMail\'s OAuth service. Please log-in an we will take care of the rest.' , 'squalomail-for-woocommerce');?></p>
    <p id="squalomail-oauth-error" class="oauth-description"><?php esc_html_e('Error, can\'t login.' , 'squalomail-for-woocommerce');?></p>
    <p id="squalomail-oauth-connecting" class="oauth-description"><?php esc_html_e('Connection in progress' , 'squalomail-for-woocommerce');?></p>
    <p id="squalomail-oauth-connected" class="oauth-description "><?php esc_html_e('Connected! Please wait while loading next step', 'squalomail-for-woocommerce');?></p>
</fieldset>

