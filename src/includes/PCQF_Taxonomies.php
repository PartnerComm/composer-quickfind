<?php

/*
 * class PCQF_Taxonomies
 * 
 * Creates required taxonomies for content types.
*/

class PCQF_Taxonomies
{

  /**
   * Initializes plugin, similar to __construct()
   */
  public static function itit()
  {

  }

  /**
   * Create group taxonomy
   */
  public static function create_group_taxonomies() {
    $labels = array(
      'name' => _x( 'Salary Bands', 'taxonomy general name' ),
      'singular_name' => _x( 'Salary Band', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Salary Bands' ),
      'all_items' => __( 'All Salary Bands' ),
      'parent_item' => __( 'Parent Salary Band' ),
      'parent_item_colon' => __( 'Parent Salary Band:' ),
      'edit_item' => __( 'Edit Salary Band' ), 
      'update_item' => __( 'Update Salary Band' ),
      'add_new_item' => __( 'Add New Salary Band' ),
      'new_item_name' => __( 'New Salary Band' ),
      'menu_name' => __( 'Salary Bands' ),
    );  

    register_taxonomy('group',array(''), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'group' ),
    ));
  }

  /**
   * Create group head taxonomy
   */
  public static function create_group_head_taxonomies() {
    $labels = array(
      'name' => _x( 'Group Head', 'taxonomy general name' ),
      'singular_name' => _x( 'Group Head', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Group Heads' ),
      'all_items' => __( 'All Group Heads' ),
      'parent_item' => __( 'Parent Group Head' ),
      'parent_item_colon' => __( 'Parent Group Head:' ),
      'edit_item' => __( 'Edit Group Head' ), 
      'update_item' => __( 'Update Group Head' ),
      'add_new_item' => __( 'Add New Group Head' ),
      'new_item_name' => __( 'New Group Head' ),
      'menu_name' => __( 'Group Heads' ),
    );  

    register_taxonomy('group_head',array(''), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'group-head' ),
    ));
  }

  /**
   * Create plan type taxonomy
   */
  public static function create_plan_type_taxonomies() {
    $labels = array(
      'name' => _x( 'Plan type', 'taxonomy general name' ),
      'singular_name' => _x( 'Plan type', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Plan types' ),
      'all_items' => __( 'All Plan types' ),
      'parent_item' => __( 'Parent Plan type' ),
      'parent_item_colon' => __( 'Parent Plan type:' ),
      'edit_item' => __( 'Edit Plan type' ), 
      'update_item' => __( 'Update Plan type' ),
      'add_new_item' => __( 'Add New Plan type' ),
      'new_item_name' => __( 'New Plan type' ),
      'menu_name' => __( 'Plan type' ),
    );  

    register_taxonomy('plan_type',array(''), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'plan-type' ),
    ));
  }

  /**
   * Create group row taxonomy
   */
  public static function create_group_row_taxonomies() {
    $labels = array(
      'name' => _x( 'Group Row', 'taxonomy general name' ),
      'singular_name' => _x( 'Group Row', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Group Rows' ),
      'all_items' => __( 'All Group Rows' ),
      'parent_item' => __( 'Parent Group Rows' ),
      'parent_item_colon' => __( 'Parent Group Rows:' ),
      'edit_item' => __( 'Edit Group Row' ), 
      'update_item' => __( 'Update Group Row' ),
      'add_new_item' => __( 'Add New Group Row' ),
      'new_item_name' => __( 'New Group Row' ),
      'menu_name' => __( 'Group Rows' ),
    );  

    register_taxonomy('group_row',array(''), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'group-row' ),
    ));
  }

  /**
   * Create keyword taxonomy
   */
  public static function create_keyword_taxonomies() {
    $labels = array(
      'name' => _x( 'Keywords', 'taxonomy general name' ),
      'singular_name' => _x( 'Keyword', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Keywords' ),
      'all_items' => __( 'All Keywords' ),
      'parent_item' => __( 'Parent Keywords' ),
      'parent_item_colon' => __( 'Parent Keyword:' ),
      'edit_item' => __( 'Edit Keyword' ), 
      'update_item' => __( 'Update Keyword' ),
      'add_new_item' => __( 'Add New Keyword' ),
      'new_item_name' => __( 'New Keyword' ),
      'menu_name' => __( 'Keyword' ),
    );  

    register_taxonomy('keyword',array('post', 'page'), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'keyword' ),
    ));
  }

  /**
   * Create QuickFind View taxonomy
   */
  public static function create_quickfind_view_taxonomy() {
    $labels = array(
      'name' => _x( 'Quick Find View', 'taxonomy general name' ),
      'singular_name' => _x( 'Quick Find View', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Quick Find Views' ),
      'all_items' => __( 'All Quick Find Views' ),
      'parent_item' => __( 'Parent Quick Find View' ),
      'parent_item_colon' => __( 'Parent Quick Find Views:' ),
      'edit_item' => __( 'Edit Quick Find View' ), 
      'update_item' => __( 'Update Quick Find View' ),
      'add_new_item' => __( 'Add New Quick Find View' ),
      'new_item_name' => __( 'New Quick Find View' ),
      'menu_name' => __( 'Quick Find Views' )
    );  

    register_taxonomy('quickfind_view',array('post'), array(
      'hierarchical' => true,
      'public' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'quickfind-view')
    ));
  }

  /**
   * Create salary band taxonomy
   */
  public static function create_salary_band_taxonomies() {
    $labels = array(
        'name' => _x( 'Salary Band', 'taxonomy general name' ),
        'singular_name' => _x( 'Salary Band', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Salary Bands' ),
        'all_items' => __( 'All Salary Bands' ),
        'parent_item' => __( 'Parent Salary Band' ),
        'parent_item_colon' => __( 'Parent Salary Band:' ),
        'edit_item' => __( 'Edit Salary Band' ),
        'update_item' => __( 'Update Salary Band' ),
        'add_new_item' => __( 'Add New Salary Band' ),
        'new_item_name' => __( 'New Salary Band' ),
        'menu_name' => __( 'Salary Bands' ),
    );

    register_taxonomy('salary_band',array('contact'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'salary_band' ),
    ));
  }

  /**
   * Create payment schedule taxonomy
   */
  public static function create_payment_schedule_taxonomies() {
    $labels = array(
        'name' => _x( 'Payment Schedule', 'taxonomy general name' ),
        'singular_name' => _x( 'Payment Schedule', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Payment Schedules' ),
        'all_items' => __( 'All Payment Schedules' ),
        'parent_item' => __( 'Parent Payment Schedule' ),
        'parent_item_colon' => __( 'Parent Payment Schedule:' ),
        'edit_item' => __( 'Edit Payment Schedule' ),
        'update_item' => __( 'Update Payment Schedule' ),
        'add_new_item' => __( 'Add New Payment Schedule' ),
        'new_item_name' => __( 'New Payment Schedule' ),
        'menu_name' => __( 'Payment Schedules' ),
    );

    register_taxonomy('payment_schedule',array('contact'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'payment_schedule' ),
    ));
  }

  /**
   * Create QuickFind Event Type taxonomy
   */
  public static function create_event_type_taxonomy() {
    $labels = array(
      'name' => _x( 'Event Type', 'taxonomy general name' ),
      'singular_name' => _x( 'Event Type', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Event Types' ),
      'all_items' => __( 'All Event Types' ),
      'parent_item' => __( 'Parent Event Type' ),
      'parent_item_colon' => __( 'Parent Event Type:' ),
      'edit_item' => __( 'Edit Event Type' ), 
      'update_item' => __( 'Update Event Type' ),
      'add_new_item' => __( 'Add New Event Type' ),
      'new_item_name' => __( 'New Event Type' ),
      'menu_name' => __( 'Event Types' )
    );  

    register_taxonomy('event_type',array('post'), array(
      'hierarchical' => true,
      'public' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'event-type')
    ));
  }

  /**
   * Create employee location taxonomy
   */
  public static function create_location_taxonomies() {
    $labels = array(
      'name' => _x( 'Employee Locations', 'taxonomy general name' ),
      'singular_name' => _x( 'Employee Location', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Employee Locations' ),
      'all_items' => __( 'All Employee Locations' ),
      'parent_item' => __( 'Parent Employee Location' ),
      'parent_item_colon' => __( 'Parent Employee Location:' ),
      'edit_item' => __( 'Edit Employee Location' ), 
      'update_item' => __( 'Update Employee Location' ),
      'add_new_item' => __( 'Add New Employee Location' ),
      'new_item_name' => __( 'New Employee Location' ),
      'menu_name' => __( 'Employee Location' ),
    );  

    register_taxonomy('location',array('post', 'contact'), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'location' ),
    ));
  }

  /**
   * Create life event taxonomy
   */
  public static function create_life_event_taxonomies() {
    $labels = array(
        'name' => _x( 'Life Event Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Life Event Category', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Life Event Categories' ),
        'all_items' => __( 'All Life Event Categories' ),
        'parent_item' => __( 'Parent Life Event Category' ),
        'parent_item_colon' => __( 'Parent Life Event Category:' ),
        'edit_item' => __( 'Edit Life Event Category' ),
        'update_item' => __( 'Update Life Event Category' ),
        'add_new_item' => __( 'Add New Life Event Category' ),
        'new_item_name' => __( 'New Life Event Category' ),
        'menu_name' => __( 'Life Event Category' ),
    );

    register_taxonomy('life_event_cat',array('post'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'life-event-cat' ),
    ));
  }

  /**
   * Create plan feature taxonomy
   */
  public static function create_plan_feature_taxonomies() {
    $labels = array(
        'name' => _x( 'Plan Features', 'taxonomy general name' ),
        'singular_name' => _x( 'Plan Feature', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Plan Features' ),
        'all_items' => __( 'All Plan Features' ),
        'parent_item' => __( 'Parent Plan Feature' ),
        'parent_item_colon' => __( 'Parent Plan Feature:' ),
        'edit_item' => __( 'Edit Plan Feature' ),
        'update_item' => __( 'Update Plan Feature' ),
        'add_new_item' => __( 'Add New Plan Feature' ),
        'new_item_name' => __( 'New Plan Feature' ),
        'menu_name' => __( 'Plan Feature' ),
    );

    register_taxonomy('plan_feature',array('post'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'plan-feature' ),
    ));
  }

  // Add term page
  public static function pcqf_taxonomy_add_new_meta_field() {
      // this will add the custom meta field to the add new term page
      ?>
      <div class="form-field">
        <label for="term_meta[pcqf_display_name]">Display Name</label>
        <input type="text" name="term_meta[pcqf_display_name]" id="term_meta[pcqf_display_name]" value="">
        <p class="description">Enter display name for table headings</p>
      </div>
      <div class="form-field">
        <label for="term_meta[pcqf_subhead_name]">Subhead Name</label>
        <input type="text" name="term_meta[pcqf_subhead_name]" id="term_meta[pcqf_subhead_name]" value="">
        <p class="description">Enter table subheads</p>
      </div>
    <?php
  }

  // Edit term page
  public static function pcqf_taxonomy_edit_meta_field($term) {
   
    // put the term ID into a variable
    $t_id = $term->term_id;
   
    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option( "taxonomy_$t_id" ); 
    ?>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[pcqf_display_name]">Display Name</label></th>
      <td>
        <input type="text" name="term_meta[pcqf_display_name]" id="term_meta[pcqf_display_name]" value="<?php echo esc_attr( $term_meta['pcqf_display_name'] ) ? esc_attr( $term_meta['pcqf_display_name'] ) : ''; ?>">
        <p class="description">Enter display name for table headings</p>
      </td>
    </tr>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[pcqf_subhead_name]">Subhead Name</label></th>
      <td>
        <input type="text" name="term_meta[pcqf_subhead_name]" id="term_meta[pcqf_subhead_name]" value="<?php echo esc_attr( $term_meta['pcqf_subhead_name'] ) ? esc_attr( $term_meta['pcqf_subhead_name'] ) : ''; ?>">
        <p class="description">Enter table subheads</p>
      </td>
    </tr>
  <?php
  }

  // Save extra taxonomy fields callback function.
  public static function pcqf_save_taxonomy_custom_meta( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
      $t_id = $term_id;
      $term_meta = get_option( "taxonomy_$t_id" );
      $cat_keys = array_keys( $_POST['term_meta'] );
      foreach ( $cat_keys as $key ) {
        if ( isset ( $_POST['term_meta'][$key] ) ) {
          $term_meta[$key] = $_POST['term_meta'][$key];
        }
      }
      // Save the option array.
      update_option( "taxonomy_$t_id", $term_meta );
    }
  } 


}

/* EOF */
?>