<?php

add_action('wp_ajax_mcwp_sendmail', 'mcwp_sendmail');
add_action('wp_ajax_nopriv_mcwp_sendmail', 'mcwp_sendmail');
function mcwp_sendmail()
{
    global $shortcode_tags;
    $to = sanitize_email($_POST['email']);
    $uns = get_option('wpmc_mail_use_network_settings');
    $option_func = (($uns===false) ? 'get_site_option' : (($uns == 1) ? 'get_site_option' : 'get_option'));
    if (use_network_setting_email() === 'yes') {
        $wpmc_mail_message = do_shortcode(get_site_option('wpmc_mail_message'));
    } else {
        $wpmc_mail_message = do_shortcode(get_option('wpmc_mail_message'));
    }
    $option_func = (use_network_settings('wpmc_mail_use_network_settings') === 'yes') ? 'get_site_option' : 'get_option';
    $mcwp_currency = $option_func('mcwp_currency');
    //$locale='en-US'; //browser or user locale
    //$currency= $mcwp_currency;
    //$fmt = new NumberFormatter( $locale."@currency=$currency", NumberFormatter::CURRENCY );
    $curr_symbol = $mcwp_currency;
    $body = '';
	$request_type = sanitize_text_field($_REQUEST['type']);
    if ($request_type == 'cv') {
        require_once('emails/cv.php');
    } elseif ($request_type == 'fha') {
        require_once('emails/fha.php');
    } elseif ($request_type == 'va') {
        require_once('emails/va.php');
    } elseif ($request_type == 'mha') {
        require_once('emails/mha.php');
    } elseif ($request_type == 'rc') {
        require_once('emails/rc.php');
    }
    wp_mail($to, $subject, $body, email_headers());
    // $cc_body .= $body;
    if (use_network_setting_email() === 'yes') {
        $to_form = get_site_option('wpmc_one_email');
    } else {
        $to_form = get_option('wpmc_one_email');
    }
    if (preg_match('/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $to_form)) {
        $to_form = do_shortcode($to_form);
    }
    wp_mail($to_form, $cc_subject, $cc_body, email_headers());
    wp_die();
}
function body_dynamic($msg_body, $current_post)
{
    $msg_body_arr = preg_split('/\r\n|[\r\n]/', $msg_body);
	$current_post_data = array();
	foreach($current_post as $key => $value){
		$current_post_data[$key] = sanitize_text_field($value);
	}
    $newpost = $current_post_data;
    if (is_array($newpost) && isset($newpost['action'])) {
        unset($newpost['action']);
    }
    if (is_array($newpost) && isset($newpost['type'])) {
        unset($newpost['type']);
    }
    if (is_array($newpost) && isset($newpost['email'])) {
        unset($newpost['email']);
    }
    $newpost_replace = array();
    foreach ($newpost as $key => $value) {
        $newpost_replace[str_replace("_", "-", $key)] = $value;
    }
    $emailmessage = $msg_body_arr;
    foreach ($newpost_replace as $shortkey => $val) {
        $emailmessage =  str_replace('['.$shortkey.']', $val, $emailmessage);
    }

    $body_part_dynamic = '';
    foreach ($emailmessage as $key => $val) {
        if ($val != "" && !empty($val)) {
            $body_part_dynamic .= '<p>'.$val.'</p>';
        }
    }
    return $body_part_dynamic;
}
function use_network_setting_email()
{
    $uns = get_option('wpmc_mail_use_network_settings');
    if ($uns == '0') {
        return 'yes';
    } else {
        return 'no';
    }
}
function checksettings($val)
{
    $uns = get_option('wpmc_mail_use_network_settings');
    if ($uns == '0') {
        return get_site_option($val);
    } else {
        return get_option($val);
    }
}
function wpmc_one_use_network_settings()
{
    // use conventional network settings
    $uns = get_option('wpmc_one_use_network_settings');
    if ($uns == '0') {
        return 'yes';
    } else {
        return 'no';
    }
}
function use_network_settings($val)
{
    // use conventional network settings
    $uns = get_option($val);
    if ($uns == '0') {
        return 'yes';
    } else {
        return 'no';
    }
}
function calc_fields($network, $field, $re)
{
    if ($network == 'cv') {
        $set = get_option('wpmc_one_use_network_settings');
    } elseif ($network == 'fha') {
        $set = get_option('wpmc_two_use_network_settings');
    } elseif ($network == 'va') {
        $set = get_option('wpmc_three_use_network_settings');
    } elseif ($network == 'mha') {
        $set = get_option('wpmc_five_use_network_settings');
    } elseif ($network == 'rc') {
        $set = get_option('wpmc_six_use_network_settings');
    }
    if ($set == '0') {
        $option = get_site_option($field);
    } else {
        $option = get_option($field);
    }
    $option = $option == '' ? __($re, 'wpmc') : $option;
    return $option;
}
function email_headers()
{
    $from = checksettings('wpmc_mail_from');
    $from = (preg_match('/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $from)) ? $from = do_shortcode($from) : $from;
    $from_name = checksettings('wpmc_mail_from_name');
    $from_name = (preg_match('/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $from_name)) ? $from_name = do_shortcode($from_name) : $from_name;
    $reply = checksettings('wpmc_mail_reply_to');
    $reply = (preg_match('/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $reply)) ? $reply = do_shortcode($reply) : $reply;
    $reply_name = checksettings('wpmc_mail_reply_to_name');
    $reply_name = (preg_match('/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $reply_name)) ? $reply_name = do_shortcode($reply_name) : $reply_name;
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: '.$from_name.' <'.$from.'>',
        'Reply-To: '.$reply_name.' <'.$reply.'>',
    );
    return $headers;
}
function get_wpmc_option($option_name)
{
    if (is_network_admin()) {
        return get_site_option($option_name);
    } else {
        return get_option($option_name);
    }
}
function update_wpmc_option($option_name, $option_value)
{
    $option_value = sanitize_text_field($option_value);
    if (is_network_admin()) {
        return update_site_option($option_name, $option_value);
    } else {
        return update_option($option_name, $option_value);
    }
}
function delete_wpmc_option($option_name)
{
    if (is_network_admin()) {
        return delete_site_option($option_name);
    } else {
        return delete_option($option_name);
    }
}
