<?php
// Grab plugin admin object
$handler = SqualoMail_WooCommerce_Admin::connect();

// Grab all options for this particular tab we're viewing.
$options = get_option($this->plugin_name, array());

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : (isset($options['active_tab']) ? $options['active_tab'] : 'api_key');
$sqm_configured = squalomail_is_configured();

if (!$sqm_configured) {
    if ($active_tab == 'sync' || $active_tab == 'logs' ) isset($options['active_tab']) ? $options['active_tab'] : 'api_key';
}

$is_squalomail_post = isset($_POST['squalomail_woocommerce_settings_hidden']) && $_POST['squalomail_woocommerce_settings_hidden'] === 'Y';

$show_sync_tab = isset($_GET['resync']) ? $_GET['resync'] === '1' : false;

// if we have a transient set to start the sync on this page view, initiate it now that the values have been saved.
if ($sqm_configured && !$show_sync_tab && (bool) get_site_transient('squalomail_woocommerce_start_sync', false)) {
    $show_sync_tab = true;
    $active_tab = 'sync';
}

$show_campaign_defaults = true;
$has_valid_api_key = false;
$allow_new_list = true;
$only_one_list = false;
$show_wizard = true;
$clicked_sync_button = $sqm_configured && $is_squalomail_post && $active_tab == 'sync';
$has_api_error = isset($options['api_ping_error']) && !empty($options['api_ping_error']) ? $options['api_ping_error'] : null;

if (isset($options['squalomail_api_key'])) {
    try {
        if ($handler->hasValidApiKey(null, true)) {
            $has_valid_api_key = true;

            // if we don't have a valid api key we need to redirect back to the 'api_key' tab.
            if (($squalomail_lists = $handler->getSqualoMailLists()) && is_array($squalomail_lists)) {
                $show_campaign_defaults = true;
                $allow_new_list = false;
                $only_one_list = count($squalomail_lists) === 1;

            }

            // only display this button if the data is not syncing and we have a valid api key
            if ((bool) $this->getData('sync.started_at', false)) {
                $show_sync_tab = true;
            }

            //display wizard if not all steps are complete
            if ($show_sync_tab && $this->getData('validation.store_info', false) && $this->getData('validation.campaign_defaults', false) && $this->getData('validation.newsletter_settings', false)) {
                $show_wizard = false;        
            }
                
        }
    } catch (\Exception $e) {
        $has_api_error = $e->getMessage().' on '.$e->getLine().' in '.$e->getFile();
    }
}
else {
    $active_tab = 'api_key';
}


//var_dump(array('jordan' => array('active' => $active_tab, 'configured' => $sqm_configured, 'api_key' => squalomail_get_api_key()))); die();

?>

<?php if (!defined('PHP_VERSION_ID') || (PHP_VERSION_ID < 70000)): ?>
    <div data-dismissible="notice-php-version" class="error notice notice-error">
        <p><?php esc_html_e('SqualoMail says: Please upgrade your PHP version to a minimum of 7.0', 'squalomail-for-woocommerce'); ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($has_api_error)): ?>
    <div data-dismissible="notice-api-error" class="error notice notice-error is-dismissible">
        <p><?php esc_html_e("SqualoMail says: API Request Error - ".$has_api_error, 'squalomail-for-woocommerce'); ?></p>
    </div>
<?php endif; ?>

<!-- Create a header in the default WordPress 'wrap' container -->
<div class="sqm-mc-woocommerce-settings wrap">
    
<svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" width="70" height="80" viewBox="0 0 80 82" version="1.1" id="svg8" sodipodi:docname="squalomail-shark-basic.svg" inkscape:version="0.92.3 (2405546, 2018-03-11)">
  <defs id="defs2">
    <clipPath clipPathUnits="userSpaceOnUse" id="clipPath3987">
      <rect id="rect3989" width="81.340477" height="80.962494" x="67.204163" y="99.998817" style="stroke-width:0.62043113"></rect>
    </clipPath>
  </defs>
  <sodipodi:namedview id="base" pagecolor="#ffffff" bordercolor="#666666" borderopacity="1.0" inkscape:pageopacity="0.0" inkscape:pageshadow="2" inkscape:zoom="0.98994949" inkscape:cx="113.8152" inkscape:cy="198.71572" inkscape:document-units="mm" inkscape:current-layer="layer1" showgrid="false" inkscape:window-width="1853" inkscape:window-height="1025" inkscape:window-x="67" inkscape:window-y="27" inkscape:window-maximized="1"></sodipodi:namedview>
  <metadata id="metadata5">
    <rdf:rdf>
      <cc:work rdf:about="">
        <dc:format>image/svg+xml</dc:format>
        <dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"></dc:type>
        <dc:title></dc:title>
      </cc:work>
    </rdf:rdf>
  </metadata>
  <g inkscape:label="Layer 1" inkscape:groupmode="layer" id="layer1" transform="translate(0,-215)">
    <image y="99.99881" x="67.204163" id="image3775" xlink:href="data:image/png;base64,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
" style="image-rendering:optimizeQuality" preserveAspectRatio="none" height="82.550003" width="77.258331" clip-path="url(#clipPath3987)" transform="translate(-66.706675,115.48763)"></image>
  </g>
</svg>
    
    <p class="sqm-mc-woocommerce-settings-subtitles">
        <?php
        
        $allowed_html = array(
			'br' => array()
        );
        
        if ($active_tab == 'api_key' ) {
            wp_kses(_e('Add SqualoMail for WooCommerce to build custom segments,<br/>send automations, and track purchase activity in SqualoMail', 'squalomail-for-woocommerce'), $allowed_html);
        }
 
        if ($active_tab == 'store_info' && $has_valid_api_key) {
            wp_kses(_e('Please provide a bit of information<br/>about your WooCommerce store', 'squalomail-for-woocommerce'), $allowed_html);
        }
 
        if ($active_tab == 'campaign_defaults' ) {
            wp_kses(_e('Please review the audience default<br/>campaign information', 'squalomail-for-woocommerce'), $allowed_html);
        }
 
        if ($active_tab == 'newsletter_settings' ) {
            if ($only_one_list) {
                wp_kses(_e('Please apply your <br/>audience settings.', 'squalomail-for-woocommerce'), $allowed_html);
            }
            else {
                wp_kses(_e('Please apply your audience settings. ', 'squalomail-for-woocommerce'), $allowed_html);
                wp_kses(_e('If you don’t<br/>have an audience, you can choose to create one', 'squalomail-for-woocommerce'), $allowed_html);    
            }
        }
        if ($active_tab == 'sync' && $show_sync_tab) {
            if (squalomail_is_done_syncing()) {
                wp_kses(_e('Sweet! You\'re connected with<br/>SqualoMail and syncing data', 'squalomail-for-woocommerce'), $allowed_html);
            }
            else {
                wp_kses(_e('Connect your WooCommerce store to a<br/>SqualoMail audience in less than 60 seconds', 'squalomail-for-woocommerce'), $allowed_html);
            }
        }
 
        if ($active_tab == 'logs' && $show_sync_tab) {
            wp_kses(_e('Log events from the <br/>SqualoMail plugin', 'squalomail-for-woocommerce'), $allowed_html);
        }
        ?>
    </p>
    <?php if($show_wizard): ?>  
        <div class="nav-wizard-wrapper">
            <div class="wizard-tab <?php echo $active_tab == 'api_key' ? 'wizard-tab-active' : ''; ?>" >
                <a href="?page=squalomail-woocommerce&tab=api_key" class="marker"></a>
                <div class="wizard-tab-tooltip wizard-tab-tooltip-api-key "><?= esc_html_e('Connect', 'squalomail-for-woocommerce');?>
                    <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="14.498" width="20" height="20" transform="rotate(45 14.498 0)" fill="white"/>
                    </svg>
                </div>
            </div>

            <?php if ($has_valid_api_key) : ?>    
                <div class="wizard-tab <?php echo $active_tab == 'store_info' ? 'wizard-tab-active' : ''; ?>">
                    <a href="?page=squalomail-woocommerce&tab=store_info" class="marker"></a>
                    <div class="wizard-tab-tooltip wizard-tab-tooltip-store-info "><?= esc_html_e('Store Settings', 'squalomail-for-woocommerce');?>
                        <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="14.498" width="20" height="20" transform="rotate(45 14.498 0)" fill="white"/>
                        </svg>
                    </div>
                </div>
            <?php else: ?>
                <div class="wizard-tab">
                    <span class="marker-disabled"></span>
                </div>      
            <?php endif; ?>

            <?php if ($handler->hasValidStoreInfo() && $show_campaign_defaults && $this->getData('validation.store_info', false)) : ?>  
                <div class="wizard-tab <?php echo $active_tab == 'campaign_defaults' ? 'wizard-tab-active' : ''; ?>">
                    <a href="?page=squalomail-woocommerce&tab=campaign_defaults" class="marker"></a>
                    <div class="wizard-tab-tooltip wizard-tab-tooltip-store-info "><?= esc_html_e('Audience Defaults', 'squalomail-for-woocommerce');?>
                        <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="14.498" width="20" height="20" transform="rotate(45 14.498 0)" fill="white"/>
                        </svg>
                    </div>
                </div>
            <?php else: ?>
                <div class="wizard-tab">
                    <span class="marker-disabled"></span>
                </div>  
            <?php endif; ?>

            <?php if ($handler->hasValidCampaignDefaults() && $this->getData('validation.campaign_defaults', false)) : ?>  
                <div class="wizard-tab <?php echo $active_tab == 'newsletter_settings' ? 'wizard-tab-active' : ''; ?>">
                    <a href="?page=squalomail-woocommerce&tab=newsletter_settings" class="marker"></a>
                    <div class="wizard-tab-tooltip wizard-tab-tooltip-store-info "><?= esc_html_e('Audience Settings', 'squalomail-for-woocommerce');?>
                        <svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="14.498" width="20" height="20" transform="rotate(45 14.498 0)" fill="white"/>
                        </svg>
                    </div>
                </div>
            <?php else: ?>
                <div class="wizard-tab">
                    <span class="marker-disabled"></span>
                </div>    
            <?php endif; ?>
        </div>     
    <?php else: ?>
        <div class="nav-tab-wrapper">
            <?php if($has_valid_api_key): ?>
                <?php if ($active_tab == 'api_key'): ?>
                    <a href="?page=squalomail-woocommerce&tab=api_key" class="nav-tab <?php echo $active_tab == 'api_key' ? 'nav-tab-active' : ''; ?>"><?= esc_html_e('Connect', 'squalomail-for-woocommerce');?></a>
                <?php endif ;?>
                <a href="?page=squalomail-woocommerce&tab=sync" class="nav-tab <?php echo $active_tab == 'sync' ? 'nav-tab-active' : ''; ?>"><?= esc_html_e('Overview', 'squalomail-for-woocommerce');?></a>
                <a href="?page=squalomail-woocommerce&tab=store_info" class="nav-tab <?php echo $active_tab == 'store_info' ? 'nav-tab-active' : ''; ?>"><?= esc_html_e('Store Settings', 'squalomail-for-woocommerce');?></a>
                <?php if ($handler->hasValidStoreInfo()) : ?>
                    <?php if($show_campaign_defaults): ?>
                        <a href="?page=squalomail-woocommerce&tab=campaign_defaults" class="nav-tab <?php echo $active_tab == 'campaign_defaults' ? 'nav-tab-active' : ''; ?>"><?= esc_html_e('Audience Defaults', 'squalomail-for-woocommerce');?></a>
                    <?php endif; ?>
                    <?php if($handler->hasValidCampaignDefaults()): ?>
                        <a href="?page=squalomail-woocommerce&tab=newsletter_settings" class="nav-tab <?php echo $active_tab == 'newsletter_settings' ? 'nav-tab-active' : ''; ?>"><?= esc_html_e('Audience Settings', 'squalomail-for-woocommerce');?></a>
                    <?php endif; ?>
                <?php endif;?>
                <a href="?page=squalomail-woocommerce&tab=logs" class="nav-tab <?php echo $active_tab == 'logs' ? 'nav-tab-active' : ''; ?>"><?= esc_html_e('Logs', 'squalomail-for-woocommerce');?></a>
            <?php endif; ?>
        </div> 
    <?php endif; ?>
    
    <?php
        $settings_errors = get_settings_errors();
        if (!$show_wizard || ($show_wizard && isset($settings_errors[0]) && $settings_errors[0]['type'] != 'success' )) {
            echo squalomail_settings_errors();
        }
    ?>

    <?php if ($active_tab != 'sync'): ?>
    <div class="tab-content-wrapper">
    <?php endif; ?>
        <form id="squalomail_woocommerce_options" method="post" name="cleanup_options" action="options.php">
            <div class="box">
                <?php if ($show_wizard) : ?>
                    <input type="hidden" name="squalomail_woocommerce_wizard_on" value=1>
                <?php endif; ?>
                
                <input type="hidden" name="squalomail_woocommerce_settings_hidden" value="Y">
              
                <?php
                    if (!$clicked_sync_button) {
                        settings_fields($this->plugin_name);
                        do_settings_sections($this->plugin_name);
                        include('tabs/notices.php');
                    }
                ?>
            </div>
            

            <input type="hidden" name="<?php echo $this->plugin_name; ?>[squalomail_active_tab]" value="<?php echo esc_attr($active_tab); ?>"/>

            <?php if ($active_tab == 'api_key' ): ?>
                <?php include_once 'tabs/api_key.php'; ?>
            <?php endif; ?>

            <?php if ($active_tab == 'store_info' && $has_valid_api_key): ?>
                <?php include_once 'tabs/store_info.php'; ?>
            <?php endif; ?>

            <?php if ($active_tab == 'campaign_defaults' ): ?>
                <?php include_once 'tabs/campaign_defaults.php'; ?>
            <?php endif; ?>

            <?php if ($active_tab == 'newsletter_settings' ): ?>
                <?php include_once 'tabs/newsletter_settings.php'; ?>
            <?php endif; ?>

            <?php if ($active_tab == 'sync' && $show_sync_tab): ?>
                <?php include_once 'tabs/store_sync.php'; ?>
            <?php endif; ?>

            <?php if ($active_tab == 'logs' && $show_sync_tab): ?>
                <?php include_once 'tabs/logs.php'; ?>
            <?php endif; ?>
            <?php 
                if ($active_tab !== 'api_key' && $active_tab !== 'sync' && $active_tab !== 'logs') {
                    if ($active_tab == 'newsletter_settings' && !squalomail_is_configured()) {
                        $submit_button_label = __('Start sync','squalomail-for-woocommerce');
                    }
                    else $submit_button_label = !$show_wizard ? __('Save all changes') : __('Next');
                    submit_button($submit_button_label, 'primary tab-content-submit','squalomail_submit', TRUE);
                }
            ?>
        </form>
        
        <?php if ($active_tab == 'api_key'): ?>
            <?php include_once 'tabs/api_key_create_account.php'; ?>
        <?php endif; ?>
        
    <?php if ($active_tab != 'sync'): ?>
    </div>
    <?php endif; ?>
    
</div><!-- /.wrap -->
