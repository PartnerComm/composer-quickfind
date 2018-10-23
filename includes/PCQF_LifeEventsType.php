<?php

// ***************** CLASS PCQF_LifeEventsType *****************
// ************************************************************
/*
 * Description:
 * Creates the Life Events content type
*/
// ************************************************************

class PCQF_LifeEventsType
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
    add_filter('manage_life-event-type_posts_columns', array(&$this, 'set_custom_life_event_columns'));
    add_filter('manage_life-event-type_posts_custom_column', array(&$this, 'life_event_custom_columns'), 2, 2 );
    
  }

  // ***************** CLASS METHODS *************************
  public function init_content_type()
  {
    register_post_type( 'life-event-type',
    array(
          'labels' => array(
          'name' => _x('Life Events', 'post type general name'),
          'singular_name' => _x('Life Event', 'post type singular name'),
          'add_new' => _x('Add New', 'life-event'),
          'add_new_item' => __('Add New Life Event'),
          'edit_item' => __('Edit Life Event'),
          'new_item' => __('New Life Event'),
          'all_items' => __('All Life Events'),
          'view_item' => __('View Life Event'),
          'search_items' => __('Search Life Events'),
          'not_found' =>  __('No Life Events found'),
          'not_found_in_trash' => __('No Life Events found in Trash'),
          'parent_item_colon' => '',
          'menu_name' => 'Life Events'
        ),
      'menu_icon' => 'dashicons-welcome-learn-more',
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields', 'revisions'),
      'taxonomies' => $this->taxonomies
      )
    );
  }

  // ***************** ADMIN METHODS ************************
  // moves id column to before the date column on the post type's admin screen
  public function set_custom_life_event_columns($columns) {
    $new = array();
    foreach($columns as $key => $title) {
    if ($key=='date')  { // Put the column before the date
      $new['keyword'] = 'Keyword';
    }
    $new[$key] = $title;
    }
    return $new;
  }

  // adds the id on the post type's admin screen
  public function life_event_custom_columns( $column, $post_id ) {
    switch ($column) {
    case 'keyword':
      $taxonomy = 'keyword';
      $post_type = get_post_type($post_id);
      $terms = get_the_terms($post_id, $taxonomy);
        if (!$terms || is_wp_error( $terms )) break;
      foreach($terms as $term) {
          echo "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " .esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
      }
      break;

    default:
        break;
    } // end switch

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
