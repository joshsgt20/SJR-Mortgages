<?php

function mcwp_shortcode($atts = array(), $content = null, $tag = '')
{
    //wpmc_enqueue(); //Load CSS & Js files
    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    // override default attributes with user attributes
    $atts = shortcode_atts(array('type' => 'cv',), $atts, $tag);

    $calTemplate2 = '';
    $option_func = (use_network_settings('wpmc_mail_use_network_settings') === 'yes') ? 'get_site_option' : 'get_option';
    $mcwp_currency = $option_func('mcwp_currency');
    $curr_symbol = $mcwp_currency;

    $wrap_class = '';
    if ($atts['type'] == 'cv') {
        require_once('views/conventional.php');
        $wrap_class = 'mcalc-conventional';
    } elseif ($atts['type'] == 'fha') {
        require_once('views/fha.php');
        $wrap_class = 'mcalc-fha';
    } elseif ($atts['type'] == 'va') {
        require_once('views/va.php');
        $wrap_class = 'mcalc-va';
    } elseif ($atts['type'] == 'mha') {
        require_once('views/mha.php');
        $wrap_class = 'mcalc-ha';
    } elseif ($atts['type'] == 'rc') {
        require_once('views/rc.php');
        $wrap_class = 'mcalc-refi';
    }
    $cal_form = '<form class="mcalc '.$wrap_class.' mcalc-color" name="'.$atts['type'].'" id="id_'.$atts['type'].'">
    '.$calculator_layout.'
      <input type="hidden" name="action" value="mcwp_sendmail"/>
    </form>';

    return $cal_form;
}
function wporg_shortcodes_init()
{
    add_shortcode('mcwp', 'mcwp_shortcode');
}
add_action('init', 'wporg_shortcodes_init');
