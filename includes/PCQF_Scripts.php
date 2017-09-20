<?php

/*
 * class PCQF_Scripts
 * 
 * Adds required scripts and styels
*/

class PCQF_Scripts
{

  // Initializes plugin, similar to __construct()
  public static function init()
  {

  }

  // Enqueue scripts
  public static function pcqf_enqueue_scripts() 
  {
    wp_enqueue_script('pcqf-js', plugins_url( '../js/pcqf.js', __FILE__ ), array('jquery', 'cookies'), '', true);
    wp_enqueue_script('cookies', plugins_url( '../js/cookies.js', __FILE__ ), array(), '', true);
    //wp_enqueue_script( 'remodal', plugins_url('../js/remodal.min.js', __FILE__ ), array(), true);
    wp_localize_script('pcqf-js', 'pcqf_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
  }

  // Enqueue styles
  public static function pcqf_enqueue_styles()
  {
    wp_enqueue_style( 'pcqf-style', plugins_url( '../css/pcqf.css', __FILE__ ), array(), '');
  }

}

/* EOF */
?>