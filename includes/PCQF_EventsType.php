<?php

// ***************** CLASS PCMM_PremiumType *****************
// ************************************************************
/*
 * Description:
 * Creates the Event content type
*/
// ************************************************************

class PCQF_EventsType
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

    // 
    add_action('init', array(&$this, 'event_init'));

    // creates the post meta box
    add_action('add_meta_boxes', array(&$this, 'add_event_meta_box')); 

    // saves the meta box contents on save
    add_action('save_post', array(&$this, 'save_event_details'));

    // adds a new column to the post type's admin page
    add_filter('manage_event_posts_columns', array(&$this, 'set_custom_event_columns'), 2, 2);

    // adds the info itself to the new column on the post type's admin post page
    add_filter('manage_event_posts_custom_column', array(&$this, 'event_custom_columns'), 2, 2 );
    
  }

  // ***************** CLASS METHODS *************************
  public function init_content_type()
  {
    register_post_type( 'event',
      array(
        'labels' => array(
          'name' => _x('Events', 'post type general name'),
          'singular_name' => _x('Event', 'post type singular name'),
          'add_new' => _x('Add New', 'Event'),
          'add_new_item' => __('Add New Event'),
          'edit_item' => __('Edit Event'),
          'new_item' => __('New Event'),
          'all_items' => __('All Events'),
          'view_item' => __('View Event'),
          'search_items' => __('Search Events'),
          'not_found' =>  __('No Events found'),
          'not_found_in_trash' => __('No Events found in Trash'), 
          'parent_item_colon' => '',
          'menu_name' => 'Events'
        ),
      'public' => true,
      'menu_icon' => 'dashicons-calendar',
      'has_archive' => true,
      'supports' => array( 'title', 'editor', 'excerpt', 'page-attributes' ),
      'taxonomies' => $this->taxonomies
      )
    );
  }

  // ***************** ADMIN METHODS ************************
  // set up the meta box to hold the info
  public static function show_event_meta_box() {
    global $post; global $prefix; global $event_meta_fields;
    // Use nonce for verification
    echo '<input type="hidden" name="events_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
    
    echo '<ul>';

    foreach ($event_meta_fields as $field) {

      // get value of this field if it exists for this post
      $meta = get_post_meta($post->ID, $field['id'], true);
      $desc = '';
      if ($field['desc'] != '') {
        $desc = '<span style="font-style: italic; color: #999;">' . $field['desc'] . '</span>';
      }
      if ($field['type'] == 'select') {
        echo '<li class="'. $prefix . 'repeatable"><label style="display:block; font-weight: bold; margin-top: 1em;" for="' . $field['id'] . '">' . $field['label'] . '</label>';
        echo '<select class="' . $field['class'] . ' name="'.$field['id'].'" id="'.$field['id'].'">';  
        foreach ($field['options'] as $option) {  
          echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';  
        }
        echo '</select>' . $desc .'</li>';  
      }
      else if ($meta && is_array($meta)) {
        foreach($meta as $row) {
          echo '<li class="widefat '. $prefix . 'repeatable"><label style="display:block; font-weight: bold; margin-top: 1em;" for="' . $field['id'] . '">' . $field['label'] . '</label>';
          echo '<input class="' . $field['class'] . ' type="'.$field['inputtype'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="' . $row . '" size="60" />' . $desc . '</li>';
        }
      }
      else {
        echo '<li class="'. $prefix . 'repeatable"><label style="display:block; font-weight: bold; margin-top: 1em;" for="' . $field['id'] . '">' . $field['label'] . '</label>';
        echo '<input class="' . $field['class'] . '" type="'.$field['inputtype'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="' . $meta . '" size="60" />' . $desc . '</li>';
      }

    }

    echo '</ul>';
  }


  // add the meta box to our contact posts
  public function add_event_meta_box() {
    add_meta_box( "post_meta", "Event info", "PCQF_EventsType::show_event_meta_box", "event", "normal", "high" );
  }


  // Save the Data
  public function save_event_details($post_id) {
    global $post; global $prefix; global $event_meta_fields;
    // verify nonce
    if (!wp_verify_nonce($_POST['events_meta_box_nonce'], basename(__FILE__))) 
      return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
      return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
      if (!current_user_can('edit_page', $post_id))
        return $post_id;
      } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    // loop through fields and save the data
    foreach ($event_meta_fields as $field) {
      
      $old = get_post_meta($post_id, $field['id'], true);
      if (isset($_POST[$field['id']])) {
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
          update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $_POST[$field['id']] && $old) {
          delete_post_meta($post_id, $field['id'], $old);
        }
      }
      else {
        delete_post_meta($post_id, $field['id'], $old);
      }
    } // end foreach
    
  }

  // moves id column to before the date column on the post type's admin screen
  public function set_custom_event_columns($columns) {
    $new = array();
    foreach($columns as $key => $title) {
    if ($key=='date')  { // Put the column before the date
      $new['event_type'] = 'Event Type';
    }
    $new[$key] = $title;
    }
    return $new;
  }


  // adds the id on the post type's admin screen
  public function event_custom_columns( $column, $post_id ) {
    switch ($column) {
    case 'event_type':
      $taxonomy = 'event_type';
      $post_type = get_post_type($post_id);
      $terms = get_the_terms($post_id, 'event_type');
        if (!$terms) break;
      foreach($terms as $term) {
        echo "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " .esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
      }
      break;
    
    default:

      break;
    } // end switch

  }

  // initlize plugin, set up custom fields array
  public function event_init() {
    global $prefix; global $event_meta_fields;
    // custom prefix for fields
    $prefix = 'pcqf_';
    // sets up all the custom fields we need
    $event_meta_fields = array(
      array(
        'label' => 'Start Date',
        'desc'  => '',
        'id'  => $prefix.'event_start_date',
        'inputtype' => 'text',
        'class' => 'event_start_date'
      ),
      array(
        'label' => 'End Date',
        'desc'  => '',
        'id'  => $prefix.'event_end_date',
        'inputtype' => 'text',
        'class' => 'event_end_date'
      ),
      array(
        'label' => 'Link Text',
        'desc'  => '',
        'id'  => $prefix.'event_link_text',
        'inputtype' => 'text',
        'class' => 'event_link_text'
      ),
      array(
        'label' => 'Link URL',
        'desc'  => '',
        'id'  => $prefix.'event_link_url',
        'inputtype' => 'text',
        'class' => 'event_url'
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
