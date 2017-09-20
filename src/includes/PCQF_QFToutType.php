<?php

// ***************** CLASS PCQF_QFToutType *****************
// ************************************************************
/*
 * Description:
 * Creates the QF Tout content type
*/
// ************************************************************

class PCQF_QFToutType
{

  // ***************** CLASS PROPERTIES ***********************
  // ** Defines class properties and accessed through getters & setters
  private $taxonomies = array('category');

  // ***************** CONSTRUCTOR *****************
  function __construct($supported_tax=null)
  {
    if(isset($supported_tax))
    {
      $this->set_taxonomy_array($supported_tax);
    }

    add_action('init', array(&$this, 'init_content_type'));
    
  }

  // ***************** CLASS METHODS *************************

  function init_content_type() {
    register_post_type( 'qf_tout',
      array(
        'labels' => array(
          'name' => _x('QF Touts', 'post type general name'),
          'singular_name' => _x('QF Tout', 'post type singular name'),
          'add_new' => _x('Add New', 'qf_tout'),
          'add_new_item' => __('Add New QF Tout'),
          'edit_item' => __('Edit QF Tout'),
          'new_item' => __('New QF Tout'),
          'all_items' => __('All QF Touts'),
          'view_item' => __('View QF Tout'),
          'search_items' => __('Search QF Touts'),
          'not_found' =>  __('No QF Touts found'),
          'not_found_in_trash' => __('No QF Touts found in Trash'), 
          'parent_item_colon' => '',
          'menu_name' => 'QF Touts'
        ),
      'menu_icon' => 'dashicons-screenoptions',
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields', 'revisions', 'page-attributes' ),
      'taxonomies' => $this->taxonomies
      )
    );
  }

  // ***************** GETTERS & SETTERS *********************
  public function get_taxonomy_array()
  {
    return $this->taxonomies;
  }

  public function set_taxonomy_array($tax_array)
  {
    $this->taxonomies = $tax_array;
  }

}

/* EOF */
?>