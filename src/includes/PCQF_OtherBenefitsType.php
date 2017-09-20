<?php

// ***************** CLASS PCQF_OtherBenefitsType *****************
// ************************************************************
/*
 * Description:
 * Creates the Other Benefits content type
*/
// ************************************************************

class PCQF_OtherBenefitsType
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
    add_filter('manage_other-type_posts_columns', array(&$this, 'set_custom_other_columns'));
    add_filter('manage_other-type_posts_custom_column', array(&$this, 'other_custom_columns'), 2, 2 );
    
  }

  // ***************** CLASS METHODS *************************
  public function init_content_type()
  {
    register_post_type( 'other-type',
    array(
          'labels' => array(
          'name' => _x('Other Benefits Type', 'post type general name'),
          'singular_name' => _x('Other Benefits Type', 'post type singular name'),
          'add_new' => _x('Add New', 'other-type'),
          'add_new_item' => __('Add New Other Benefits Type'),
          'edit_item' => __('Edit Other Benefits Type'),
          'new_item' => __('New Other Benefits Type'),
          'all_items' => __('All Other Benefits Types'),
          'view_item' => __('View Other Benefits Type'),
          'search_items' => __('Search Other Benefits Types'),
          'not_found' =>  __('No Other Benefits Types found'),
          'not_found_in_trash' => __('No Other Benefits Types found in Trash'), 
          'parent_item_colon' => '',
          'menu_name' => 'Other Benefits'
        ),
      'menu_icon' => 'dashicons-format-status',
      'public' => true,
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields', 'revisions', 'page-attributes'),
      'taxonomies' => $this->taxonomies
      )
    );
  }

  // ***************** ADMIN METHODS ************************
  // moves id column to before the date column on the post type's admin screen
  public function set_custom_other_columns($columns) {
    $new = array();
    foreach($columns as $key => $title) {
    if ($key=='date')  { // Put the column before the date
      $new['keyword'] = 'Keyword';
      $new['location'] = "Location";
      $new['group_head'] = 'Group Head';
      $new['group_row'] = 'Group Row';
    }
    $new[$key] = $title;
    }
    return $new;
  }

  // adds the id on the post type's admin screen
  public function other_custom_columns( $column, $post_id ) {
    switch ($column) {
    case 'keyword':
      $taxonomy = 'keyword';
      $post_type = get_post_type($post_id);
      $terms = get_the_terms($post_id, $taxonomy);
      foreach($terms as $term) {
          echo "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " .esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
      }
      break;

    case 'location':
      $taxonomy = 'location';
      $post_type = get_post_type($post_id);
      $terms = get_the_terms($post_id, $taxonomy);
      foreach($terms as $term) {
          echo "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " .esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
      }
      break;

    case 'group_head':
      $taxonomy = 'group_head';
      $post_type = get_post_type($post_id);
      $terms = get_the_terms($post_id, $taxonomy);
      foreach($terms as $term) {
          echo "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " .esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
      }
      break;

    case 'group_row':
      $taxonomy = 'group_row';
      $post_type = get_post_type($post_id);
      $terms = get_the_terms($post_id, $taxonomy);
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