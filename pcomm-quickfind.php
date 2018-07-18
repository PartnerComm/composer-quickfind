<?php

/*
 * Plugin Name: PComm QuickFind
 * Plugin URI: http://www.partnercomm.net
 * Description: Complete QuickFind plugin including all taxonomies, synonym manager, and sort order/layout control.
 * Version: 0.9.0
 * Author: PartnerComm
 * Author URI: http://www.partnercomm.net
*/

/*******************************************************************************
 ** INCLUDES **
 *******************************************************************************/

include_once('includes/PCQF_Model.php');
include_once('includes/PCQF_Cookie.php');
include_once('includes/PCQF_Scripts.php');
include_once('includes/PCQF_Views.php');
include_once('includes/PCQF_Taxonomies.php');
include_once('includes/PCQF_MedicalType.php');
include_once('includes/PCQF_VideoType.php');
include_once('includes/PCQF_DentalVisionType.php');
include_once('includes/PCQF_AccountsType.php');
include_once('includes/PCQF_IncomeProtectionType.php');
include_once('includes/PCQF_WellnessType.php');
include_once('includes/PCQF_RetirementType.php');
include_once('includes/PCQF_OtherBenefitsType.php');
include_once('includes/PCQF_QFToutType.php');
include_once('includes/PCQF_EventsType.php');
include_once('includes/PCQF_TipType.php');
include_once('includes/PCQF_Admin.php');


/**
 * Controller: PCommQuickFind
 */
class PCommQuickFind 
{

  /*******************************************************************************
   ** PROPERTIES **
   *******************************************************************************/
  private static $qf_model;

  /**
   * Constructor method:
   * Creates required taxonomies and content types
   */
  function __construct()
  {
    /*******************************************************************************
     ** FILTERS **
     *******************************************************************************/
    add_filter('query_vars', 'PCQF_Model::parameter_queryvars');

    /*******************************************************************************
     ** HOOKS **
     *******************************************************************************/
    add_action('init', array(&$this, 'pcqf_add_post_support'));
    add_action( 'pre_get_posts', array(&$this, 'pcqf_category_add_qftouts'));
    add_action( 'group_head_add_form_fields', 'PCQF_Taxonomies::pcqf_taxonomy_add_new_meta_field', 10, 2 );
    add_action( 'group_head_edit_form_fields', 'PCQF_Taxonomies::pcqf_taxonomy_edit_meta_field', 10, 2 );
    add_action( 'edited_group_head', 'PCQF_Taxonomies::pcqf_save_taxonomy_custom_meta', 10, 2 );  
    add_action( 'create_group_head', 'PCQF_Taxonomies::pcqf_save_taxonomy_custom_meta', 10, 2 );
    add_shortcode( 'qflink', array(&$this, 'pcqf_qflink_shortcode'));
    add_shortcode( 'user_selections', array(&$this, 'pcqf_user_selections_shortcode') );

    /*******************************************************************************
     ** CONSTANTS **
     *******************************************************************************/
    define('PCQFURL', WP_PLUGIN_URL."/".dirname(plugin_basename(__FILE__)));
    define('PCQFPATH', WP_PLUGIN_DIR."/".dirname(plugin_basename(__FILE__)));

    /*******************************************************************************
     ** INITIALIZE ALL TAXONOMIES **
     *******************************************************************************/
    add_action('init', 'PCQF_Taxonomies::create_group_head_taxonomies', 0);
    add_action('init', 'PCQF_Taxonomies::create_group_row_taxonomies', 0);
    add_action('init', 'PCQF_Taxonomies::create_keyword_taxonomies', 0);
    add_action('init', 'PCQF_Taxonomies::create_quickfind_view_taxonomy', 0);
    add_action('init', 'PCQF_Taxonomies::create_plan_type_taxonomies', 0);
    add_action('init', 'PCQF_Taxonomies::create_location_taxonomies', 0);
    add_action('init', 'PCQF_Taxonomies::create_salary_band_taxonomies', 0);
    add_action('init', 'PCQF_Taxonomies::create_payment_schedule_taxonomies', 0);
    add_action('init', 'PCQF_Taxonomies::create_life_event_taxonomies', 0);
    add_action('init', 'PCQF_Taxonomies::create_plan_feature_taxonomies', 0);
    add_action( 'rest_api_init', array($this, 'pcqf_register_api_route'));

    /*******************************************************************************
     ** INITIALIZE CONTENT TYPES **
     *******************************************************************************/

    /**
     * Set taxonomies for Medical content type and initialize
     */
    $medical_tax = array('group_head', 'group_row', 'quickfind_view', 'keyword', 'location', 'salary_band', 'coverage', 'payment_schedule', 'life_event_cat', 'plan_feature');
    $medical_type = new PCQF_MedicalType($medical_tax);

    /**
     * Set taxonomies for Dental Vision content type and initialize
     */
    $dental_vision_tax = array('group_head', 'group_row', 'quickfind_view', 'keyword', 'location', 'salary_band', 'coverage', 'payment_schedule', 'life_event_cat', 'plan_feature');
    $dental_vision_type = new PCQF_DentalVisionType($dental_vision_tax);

    /**
     * Set taxonomies for Accounts content type and initialize
     */
    $accounts_tax = array('group_head', 'group_row', 'quickfind_view', 'keyword', 'location', 'salary_band', 'coverage', 'payment_schedule', 'life_event_cat');
    $accounts_type = new PCQF_AccountsType($accounts_tax);

    /**
     * Set taxonomies for Income Protection content type and initialize
     */
    $income_tax = array('group_head', 'group_row', 'quickfind_view', 'keyword', 'location', 'salary_band', 'coverage', 'payment_schedule', 'life_event_cat');
    $income_type = new PCQF_IncomeProtectionType($income_tax);

    /**
     * Set taxonomies for Wellness content type and initialize
     */
    $wellness_tax = array('group_head', 'group_row', 'quickfind_view', 'keyword', 'location', 'salary_band', 'coverage', 'payment_schedule', 'life_event_cat');
    $wellness_type = new PCQF_WellnessType($wellness_tax);

    /**
     * Set taxonomies for Retirement content type and initialize
     */
    $retirement_tax = array('group_head', 'group_row', 'quickfind_view', 'keyword', 'location', 'salary_band', 'coverage', 'payment_schedule', 'life_event_cat');
    $retirement_type = new PCQF_RetirementType($retirement_tax);

    /**
     * Set taxonomies for Other Benefits content type and initialize
     */
    $other_tax = array('group_head', 'group_row', 'quickfind_view', 'keyword', 'location', 'salary_band', 'coverage', 'payment_schedule', 'life_event_cat');
    $other_type = new PCQF_OtherBenefitsType($other_tax);

    /**
     * Set taxonomies for QF Tout content type and initialize
     */
    $qf_tout_tax = array( 'keyword', 'category', 'post_tag');
    $qf_tout = new PCQF_QFToutType($qf_tout_tax);

    /**
     * Set taxonomies for Tip content type and initialize
     */
    //$tip_tax = array('plan_type', 'keyword');
    //$tip = new PCQF_TipType($tip_tax);

    /**
     * Set taxonomies for Premium content type and initialize
     */
    //$premium_tax = array( 'plan', 'plan_type', 'group', 'detail_topics', 'pcomm_post_format' );
    //$premium = new PCQF_PremiumType($premium_tax);


    /*******************************************************************************
     ** ENQUEUE SCRIPTS AND STYLES FOR FRONT-END **
     *******************************************************************************/
    add_action('wp_enqueue_scripts', 'PCQF_Scripts::pcqf_enqueue_styles', 20);
    add_action('wp_enqueue_scripts', 'PCQF_Scripts::pcqf_enqueue_scripts');
    
    /**
     * Create instance of Model
     * @var PCQF_Model
     */
    self::$qf_model = new PCQF_Model();

    /**
     * Install plugin and create/update database
     */
    register_activation_hook(__FILE__, 'PCQF_Model::pcqf_install');

    /*******************************************************************************
     ** ADMIN INIT **
     *******************************************************************************/
    PCQF_Admin::init();

    /**
     * Admin scripts and styles
     */
    add_action('admin_enqueue_scripts', 'PCQF_Admin::pcqf_admin_enqueue_styles', 12);
    add_action('admin_enqueue_scripts', 'PCQF_Admin::pcqf_admin_enqueue_scripts');

    /**
     * Admin AJAX methods
     */
    add_action('wp_ajax_pcqf_get_posts_by_group','PCQF_Admin::pcqf_get_posts_by_group');
    add_action('wp_ajax_pcqf_get_edit_group_post_form','PCQF_Admin::pcqf_get_edit_group_post_form');
    add_action('wp_ajax_pcqf_update_group_post_settings','PCQF_Admin::pcqf_update_group_post_settings');
    add_action('wp_ajax_pcqf_delete_group_keyword_by_ajax','PCQF_Admin::pcqf_delete_group_keyword_by_ajax');
    add_action('wp_ajax_pcqf_get_add_group_keyword_form','PCQF_Admin::pcqf_get_add_group_keyword_form');
    add_action('wp_ajax_pcqf_add_group_keyword_by_ajax','PCQF_Admin::pcqf_add_group_keyword_by_ajax');
    add_action('wp_ajax_pcqf_get_posts_by_keyword','PCQF_Admin::pcqf_get_posts_by_keyword');
    add_action('wp_ajax_pcqf_get_posts_by_keyword_taxonomy','PCQF_Admin::pcqf_get_posts_by_keyword_taxonomy');
    add_action('wp_ajax_pcqf_get_update_form', 'PCQF_Admin::pcqf_get_update_form');
    add_action('wp_ajax_pcqf_update_keyword_settings', 'PCQF_Admin::pcqf_update_keyword_settings');
  }

  /*******************************************************************************
   ** METHODS **
   *******************************************************************************/
  public function pcqf_add_post_support() 
  {
    $supports = array('page-attributes', 'revisions', 'thumbnail');
    add_post_type_support('post', $supports);
    remove_post_type_support('post', 'post-formats');
  }

  public function pcqf_get_keyword_results($term)
  {
    $key_res = self::$qf_model->get_keyword_posts($term);

    return $key_res;
  }

  public function pcqf_qflink_shortcode($atts) {
    $a = shortcode_atts(array(
        'synonym' => 'Medical Options',
        'linktext' => 'Medical Options',
        'wrap' => 'false',
        'icon' => 'false',
        'href' => 'false'
    ), $atts);

    //$clean_syn = html_entity_decode($a['synonym'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $syn = self::$qf_model->get_synonym($a['synonym']);
    //debug($syn);

    if(!empty($syn)) {
      $qarray = array(
          'syn' => $syn->synonym,
          'childslug' => $syn->term_slug,
          'parentslug' => $syn->parent_slug,
          'parentname' => $syn->parent_name
      );

      $qstring = http_build_query($qarray, '', '&amp;');

      $href = '/keyword/' . $syn->term_slug . '/?' . $qstring . '/';

      if($a['href'] == 'true') {
        $link = $href;
      } else {
        if($a['icon'] == 'true') {
          $link_tag = '<a href="' . $href . '">' . $a['linktext'] . '<i class="qf_link fa fa-angle-right" style="margin-left:0.25em;"></i></a>';
        } else {
          $link_tag = '<a href="' . $href . '">' . $a['linktext'] . '</a>';
        }

        if($a['wrap'] == 'true') {
          $link = '<p class="qf_link_wrap">' . $link_tag . '</p>';
        }
        else {
          $link = $link_tag;
        }
      }

      return $link;
    }

  }

  public function pcqf_user_selections_shortcode($atts) {

    $cookie = PCQF_Cookie::get_cookie();
    parse_str($cookie, $user_preferences);

    $data = shortcode_atts( array(
        'label' => 'Your selections:',
        'location' => $user_preferences['location'],
        'salary_band' => $user_preferences['salary_band'],
        'payment_schedule' => $user_preferences['payment_schedule']
    ), $atts );

    $location_object = get_term_by('slug', $data['location'], 'location');
    $salary_band_object = get_term_by('slug', $data['salary_band'], 'salary_band');
    $payment_schedule_object = get_term_by('slug', $data['payment_schedule'], 'payment_schedule');

    $location = $location_object->name;
    $salary_band = $salary_band_object->name;
    $payment_schedule = $payment_schedule_object->name;
    $selections = '<div class="user-selections">';
    $selections .= '<h3>' . $data['label'] . '</h3>';
    $selections .= '<p>I live in <strong>' . $location . '</strong>, am paid <strong>' . $payment_schedule . '</strong>, make <strong>' . $salary_band . '</strong>. <a href="/site-preferences/"><i class="fa fa-pencil"></i></a></p>';
    $selections .= '</div>';
    return $selections;
  }

  public function pcqf_category_add_qftouts($query)
  {
    // get user preferences from cookie
    $cookie = PCQF_Cookie::get_cookie();
    // parse cookie into $user_preferences array
    parse_str($cookie, $user_preferences);

    if($query->is_tax('keyword') && $query->is_main_query()) {
        $query->set('posts_per_page', -1);
        $query->set('post_type', array(
            'medical-type', 
            'dental-vision-type', 
            'accounts-type', 
            'retirement-type', 
            'wellness-type', 
            'other-type',
            'income-type',
            'contact'
        ));

        //debug($query);

      // create tax query arrays
      if ( !empty($user_preferences['location']) ) {
        $location_tax_query = array(
            'taxonomy' => 'location',
            'field' => 'slug',
            'terms' => array('all-locations', $user_preferences['location'])
        );
      }

      if ( !empty($user_preferences['salary_band']) ) {
        $salary_band_tax_query = array(
            'taxonomy' => 'salary_band',
            'field' => 'slug',
            'terms' => array('all-salary-bands', $user_preferences['salary_band'])
        );
      }

      if ( !empty($user_preferences['payment_schedule']) ) {
        $payment_schedule_tax_query = array(
            'taxonomy' => 'payment_schedule',
            'field' => 'slug',
            'terms' => array('all-payment-schedules', $user_preferences['payment_schedule'])
        );
      }

        // this would work if we wanted to show all by default
        // it gets slightly more complex as we add more taxonomies to this query
        // if ( !empty( $_COOKIE['user_location'] ) ) {
          $tax_query = array(
              $location_tax_query,
              $salary_band_tax_query,
              $payment_schedule_tax_query
          );

          $query->set( 'tax_query', $tax_query );
        // }


      // debug($query);

        
    }
    elseif ($query->is_home() && $query->is_main_query()) {
        $query->set( 'posts_per_page', '-1' );
        $query->set( 'post_type', array('qf_tout'));
        $query->set( 'tag', 'home');
        $query->set( 'orderby', 'menu_order');
        $query->set( 'order', 'ASC');
    }

    elseif($query->is_page() && $query->is_main_query()) {
        $query->set('post_type', array('contact', 'page'));
    }

    elseif($query->is_category() && $query->is_main_query()) {
        $query->set( 'posts_per_page', -1 );
        $query->set( 'post_type', array('qf_tout', 'post'));
        $query->set( 'orderby', 'menu_order');
        $query->set( 'order', 'ASC');

        $tax_query = array(
            // 'relation' => 'AND',
            // array(
            //     'taxonomy' => 'keyword',
            //     'field'    => 'slug',
            //     'terms'    => array($query->query_vars['keyword'])
            // ),
            array(
                'taxonomy' => 'location',
                'field'    => 'slug',
                'terms'    => array('all-locations', $_COOKIE['user_location'])
            )
        );

        $query->set( 'tax_query', $tax_query );

        // debug($query);

    }


  }

  public function pcqf_register_api_route() {

    register_rest_route( 'pcqf/v1', '/keyword/(?P<slug>[\w-]+)', array(
        'methods' => 'GET',
        'callback' => array($this, 'pcqf_api_callback'),

    ) );

    register_rest_route( 'pcqf/v1', '/syns/', array(
        'methods' => 'GET',
        'callback' => array($this, 'pcqf_api_get_synonyms'),

    ) );

  }

  public function pcqf_api_get_synonyms($data) {
    $syns = self::$qf_model->get_all_synonyms();
    return $syns;
  }

  public function pcqf_api_callback($data) {
    $keyword = get_term_by( 'slug', $data['slug'], 'keyword' );

    $api_query = new WP_Query(array(
      // get all posts
        'posts_per_page' => -1,
      // set post type
        'post_type' => array(
            'medical-type',
            'dental-vision-type',
            'accounts-type',
            'retirement-type',
            'wellness-type',
            'other-type',
            'income-type',
            'contact'
        ),
      // set up taxonomy query
        'tax_query' => array(
          // get posts that match all these conditions

            array(
              // check plan_type taxonomy for the id of our plan type
                'taxonomy' => 'keyword',
                'field' => 'id',
                'terms' => $keyword->term_id
            )

        )
    ));

    if ( empty( $api_query->posts ) ) {
      return new WP_Error( 'pcqf_no_quick_find_posts', 'No Quick Find Posts', array( 'status' => 404 ) );
    }

    return self::$qf_model->pcqf_make_qf_posts($api_query->posts, $keyword);
  }

} // end class definition

/**
 * Create instance of controller
 */
new PCommQuickFind();

/* EOF */
?>