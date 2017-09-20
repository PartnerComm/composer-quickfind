<?php

// ***************** CLASS PCQF_TipType *****************
// ************************************************************
/*
 * Description:
 * Creates the Tip content type
*/
// ************************************************************

class PCQF_TipType
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
    register_post_type( 'tip',
      array(
        'labels' => array(
          'name' => _x('Tips', 'post type general name'),
          'singular_name' => _x('Tip', 'post type singular name'),
          'add_new' => _x('Add New', 'tip'),
          'add_new_item' => __('Add New Tip'),
          'edit_item' => __('Edit Tip'),
          'new_item' => __('New Tip'),
          'all_items' => __('All Tips'),
          'view_item' => __('View Tip'),
          'search_items' => __('Search Tips'),
          'not_found' =>  __('No Tips found'),
          'not_found_in_trash' => __('No Tips found in Trash'), 
          'parent_item_colon' => '',
          'menu_name' => 'Tips'
        ),
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
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