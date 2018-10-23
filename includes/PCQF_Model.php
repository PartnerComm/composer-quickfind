<?php

/*
 * class PCQF_Model
 * 
 * Business logic and database interaction.
*/

class PCQF_Model
{

  /*******************************************************************************
   ** CLASS PROPERTIES **
   *******************************************************************************/
  
  

  /*******************************************************************************
   ** CONSTRUCTOR **
   *******************************************************************************/

  function __construct()
  {
    

  }

  /*******************************************************************************
   ** METHODS **
   *******************************************************************************/
  
  // Install and create table
  public static function pcqf_install()
  {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    $qftable = $wpdb->prefix . "pcomm_quickfind_order_layout";
    
    $qf_sql = "CREATE TABLE `$qftable` (
      `qfid` int(11) NOT NULL AUTO_INCREMENT,
      `post_id` int(11) NOT NULL,
      `term_id` int(11) NOT NULL,
      `term_name` varchar(255) NOT NULL,
      `term_slug` varchar(255) NOT NULL,
      `sort_order` int(11),
      `group_slug` varchar(255),
      `group_order` int(11),
      `layout` varchar(255),
      `group_layout` varchar(255),
      PRIMARY KEY (`qfid`)
    );";
    
    dbDelta($qf_sql);

  }

  /**
   * Creates new object using QuickFind sort order and layout
   * @param  object $posts   $posts object from wp_query in template
   * @param  object $keyword term object from get_term_by in template
   * @return [type]          [description]
   */
  public function get_keyword_posts($keyword)
  {
    // get user preferences from cookie
    $user_preferences = PCQF_Cookie::get_cookie();
    /*******************************************************************************
     ** NO NEED FOR THIS QUERY IF SUPPLIED BY TEMPLATE **
     *******************************************************************************/
    $qf_query = new WP_Query(array(
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
        'contact',
        'docs-type',
      ),
      // set up taxonomy query
      'tax_query' => array(
        // get posts that match all these conditions
        'relation' => 'AND',
        array(
          // check plan_type taxonomy for the id of our plan type
          'taxonomy' => 'keyword',
          'field' => 'id',
          'terms' => $keyword->term_id
        ),
        array(
          // check plan_type taxonomy for the id of our plan type
          'taxonomy' => 'location',
          'field' => 'name',
          'terms' => array('All')
        )

      )
    ));

    // set posts object from results
    $posts = $qf_query->posts;

    // Create master object to return
    $qf_obj = new stdClass();

    // Create sort order array with post slugs or group slugs
    $sort = array();

    // Create array for single posts
    $singles = array();

    // Create array for groups
    $groups = array();

    // loop through all posts and set each array
    foreach ($posts as $p)
    {
      // get plans and detail topic taxonomies
      $group_row = get_the_terms($p->ID, 'group_row');
      $group_head = get_the_terms($p->ID, 'group_head');

      // add detail topic taxonomy to posts object
      $topic_array = reset($group_row);
      $p->topic = $topic_array->name;
      $p->topic_slug = $topic_array->slug;
      $p->topic_id = $topic_array->term_id;

      // add plan name taxonomy to posts object
      $plan_array = reset($group_head);
      $p->plan = $plan_array->name;
      $p->plan_slug = $plan_array->slug;
      $p->plan_id = $plan_array->term_id;
      $t_id = $plan_array->term_id;
      $p->term_data = get_option( "taxonomy_$t_id" );

      // get order and add to posts object
      $qf_order = $this->get_qf_order($p->ID, $keyword->term_id);
      $p->sort_order = $qf_order->sort_order;
      $p->group = $qf_order->group_slug;
      $p->group_order = $qf_order->group_order;
      $p->layout = $qf_order->layout;
      $p->group_layout = $qf_order->group_layout;
      $p->keyword = $qf_order->term_name;
      $p->keyword_slug = $qf_order->term_slug;
      $p->keyword_id = $qf_order->term_id;

      // get custom fields
      // $p->employee_annual = get_post_meta($p->ID, 'pc_employee_annual', true);
      // $p->employer_annual = get_post_meta($p->ID, 'pc_employer_annual', true);
      // $p->phone_number = get_post_meta($p->ID, 'pc_phone_number', true);
      // $p->contact_url = get_post_meta($p->ID, 'pc_url', true);
      $p->custom_fields = get_post_meta($p->ID);
      $p->custom_keys = get_post_custom_keys($p->ID);
      $p->custom_content = array();
      foreach($p->custom_keys as $key)
      {
        $p->custom_content[$key] = get_post_meta($p->ID, $key, true);
      }
      $p->custom_values = get_post_custom($p->ID);
      foreach($p->custom_values as $k => $v)
      {
        $p->{$k} = maybe_unserialize($v[0]);
      }

      // populate sort array
      if(strlen($p->group) > 1)
      {
        $slug = $p->group;
        $group = true;
        $cell_content = array(
          'title' => $p->post_title,
          'post_name' => $p->post_name, 
          'content' =>$p->post_content,
          'formatted_content' => str_replace(']]>',']]&gt>', apply_filters('the_content', $p->post_content)),
          'custom_content' => $p->custom_content,
          'custom_values' => $p->custom_values,
          'group_layout' => $p->group_layout,
          'row_name' => $p->topic,
          'col_name' => $p->plan
        );
        $groups[$p->group]['group_row_order'][$p->group_order][$p->topic_slug] = array(
          'slug' => $p->topic_slug,
          'name' => $p->topic,
          'id' => $p->topic_id
        );
        $groups[$p->group]['group_col_order'][$p->group_order][$p->plan_slug] = array(
          'slug' => $p->plan_slug,
          'name' => $p->plan,
          'id' => $p->plan_id,
          'term_data' => $p->term_data
        );
        $groups[$p->group]['group_post_order'][$p->group_order] = $cell_content;

        //$groups[$p->group][$p->group_order][$p->topic_slug][$p->plan_slug] = $cell_content;
        $groups[$p->group]['row_heads'][$p->topic_slug] = array('name' => $p->topic, 'slug' => $p->topic_slug);
        $groups[$p->group]['col_heads'][$p->plan_slug] = array('name' => $p->plan, 'slug' => $p->plan_slug);
        $groups[$p->group]['layout'] = $p->layout;
        $groups[$p->group]['premium_group'] = $p->premium_group;
        $groups[$p->group]['row_order'][$p->topic_slug][$p->plan_slug] = $cell_content;
        $groups[$p->group]['col_order'][$p->plan_slug][$p->topic_slug] = $cell_content;
        $groups[$p->group]['rows_sorted'] = array();
        $groups[$p->group]['cols_sorted'] = array();
        ksort($groups[$p->group]['group_row_order']);
        ksort($groups[$p->group]['group_col_order']);
        ksort($groups[$p->group]['group_post_order']);
      }
      else
      {
        $slug = $p->post_name;
        $group = false;
        $formatted_content = str_replace(']]>',']]&gt>', apply_filters('the_content', $p->post_content));
        $singles[$p->post_name] = array(
          'layout' => $p->layout, 
          'title' => $p->post_title,
          'post_name' => $p->post_name, 
          'content' => $p->post_content, 
          'formatted_content' => $formatted_content,
          'custom_content' => $p->custom_content,
          'custom_values' => $p->custom_values
        );
      }
      $sort[$p->sort_order] = array('slug' => $slug, 'group' => $group);
    }

    // order sort array
    ksort($sort);

    $count = 0;
    $group_keys = array_keys($groups);

    // simplify group sort
    foreach($groups as $group)
    {
      $srows = array();
      $scols = array();

      // loop through row order and make single entry
      foreach($group['group_row_order'] as $sort_number )
      {
        foreach($sort_number as $key => $value)
        {
          $srows[$key] = $value;
        }
      }
      $groups[$group_keys[$count]]['rows_sorted'] = $srows;

      // loop through col order and make single entry
      foreach($group['group_col_order'] as $sort_number_col )
      {
        foreach($sort_number_col as $ckey => $cvalue)
        {
          $scols[$ckey] = $cvalue;
        }
      }
      $groups[$group_keys[$count]]['cols_sorted'] = $scols;

      $count++;
    }

    //$qf_obj->posts = $posts;
    $qf_obj->sort_order = $sort;
    debug($sort);
    $qf_obj->groups = $groups;
    $qf_obj->singles = $singles;

    return $qf_obj;
  }

  public function pcqf_make_qf_posts($posts, $keyword) {
    // Create master object to return
    $qf_obj = new stdClass();

    // Create sort order array with post slugs or group slugs
    $sort = array();

    // Create array for single posts
    $singles = array();

    // Create array for groups
    $groups = array();

    // loop through all posts and set each array
    foreach ($posts as $p)
    {
      // get thumbnail
      $thumb_id = get_post_thumbnail_id( $p->ID );
      $p->thumb_url = wp_get_attachment_url( $thumb_id );

      // get plans and detail topic taxonomies
      $group_row = get_the_terms($p->ID, 'group_row');
      $group_head = get_the_terms($p->ID, 'group_head');

      // add detail topic taxonomy to posts object
      if ( !empty($group_row) ) {
        $topic_array = reset($group_row);
        $p->topic = $topic_array->name;
        $p->topic_slug = $topic_array->slug;
        $p->topic_id = $topic_array->term_id;
      }

      // add plan name taxonomy to posts object
      if ( !empty($group_head) ) {
        $plan_array = reset($group_head);
        $p->plan = $plan_array->name;
        $p->plan_slug = $plan_array->slug;
        $p->plan_id = $plan_array->term_id;
        $t_id = $plan_array->term_id;
        $term_data = get_option( "taxonomy_$t_id" );
        if(!empty($term_data)) {
          $p->plan = $term_data['pcqf_display_name'];
        }
        $p->subhead = $term_data['pcqf_subhead_name'];
      }

      // get life event taxonomy
      $life_event_cat = get_the_terms($p->ID, 'life_event_cat');

      if(!empty($life_event_cat)) {
        $life_array = reset($life_event_cat);
        $p->life_event = $life_array->slug;
        $p->life_event_name = $life_array->name;
      }

      // get plan feature taxonomy
      $plan_feature_terms = get_the_terms($p->ID, 'plan_feature');

      if(!empty($plan_feature_terms)) {
        $feature_array = array();
        foreach($plan_feature_terms as $feature) {
          array_push($feature_array, $feature->slug);
        }
        $p->plan_features = $feature_array;
      }

      // get order and add to posts object
      $qf_order = $this->get_qf_order($p->ID, $keyword->term_id);
      //debug($qf_order);

      if($qf_order) {
        $p->sort_order = $qf_order->sort_order;
        $p->group = $qf_order->group_slug;
        $p->group_order = $qf_order->group_order;
        $p->layout = $qf_order->layout;
        $p->group_layout = $qf_order->group_layout;
        $p->keyword = $qf_order->term_name;
        $p->keyword_slug = $qf_order->term_slug;
        $p->keyword_id = $qf_order->term_id;

        $keyword_term = get_term_by('slug', $qf_order->term_slug, 'keyword');
        if(!empty($keyword_term)) {
          $keyword_parent = get_term($keyword_term->parent, 'keyword');

          $p->parent_slug = $keyword_parent->slug;
          $p->parent_name = $keyword_parent->name;
        }

      }

      // get custom fields
      // $p->employee_annual = get_post_meta($p->ID, 'pc_employee_annual', true);
      // $p->employer_annual = get_post_meta($p->ID, 'pc_employer_annual', true);
      // $p->phone_number = get_post_meta($p->ID, 'pc_phone_number', true);
      // $p->contact_url = get_post_meta($p->ID, 'pc_url', true);
      $p->custom_fields = get_post_meta($p->ID);
      $p->custom_keys = get_post_custom_keys($p->ID);
      $p->custom_content = array();
      foreach($p->custom_keys as $key)
      {
        $p->custom_content[$key] = get_post_meta($p->ID, $key, true);
      }
      $p->custom_values = get_post_custom($p->ID);
      foreach($p->custom_values as $k => $v)
      {
        $p->{$k} = maybe_unserialize($v[0]);
      }

      // populate sort array
      if(strlen($p->group) > 1)
      {
        $slug = $p->group;
        $group = true;
        $cell_content = array(
          'ID' => $p->ID,
          'title' => $p->post_title,
          'post_name' => $p->post_name,
          'post_excerpt' => $p->post_excerpt,
          'content' =>$p->post_content,
          'formatted_content' => str_replace(']]>',']]&gt>', apply_filters('the_content', $p->post_content)),
          'thumb_url' => $p->thumb_url,
          'custom_content' => $p->custom_content,
          'custom_values' => $p->custom_values,
          'group_layout' => $p->group_layout,
          'term_name' => $p->keyword,
          'term_slug' => $p->keyword_slug,
          'parent_name' => $p->parent_name,
          'parent_slug' => $p->parent_slug,
          'row_name' => $p->topic,
          'col_name' => $p->plan,
          'life_event' => $p->life_event,
          'life_event_name' => $p->life_event_name,
          'plan_features' => $p->plan_features
        );
        $groups[$p->group]['group_post_order'][$p->group_order] = $cell_content;

        if(!empty($p->topic) || !empty($p->plan)) {
          $groups[$p->group]['group_row_order'][$p->group_order][$p->topic_slug] = array(
              'slug' => $p->topic_slug,
              'name' => $p->topic,
              'id' => $p->topic_id
          );
          $groups[$p->group]['group_col_order'][$p->group_order][$p->plan_slug] = array(
              'slug' => $p->plan_slug,
              'name' => $p->plan,
              'id' => $p->plan_id,
              'subhead' => $p->subhead
          );

          $groups[$p->group]['row_heads'][$p->topic_slug] = array('name' => $p->topic, 'slug' => $p->topic_slug);
          $groups[$p->group]['col_heads'][$p->plan_slug] = array('name' => $p->plan, 'slug' => $p->plan_slug);
          $groups[$p->group]['row_order'][$p->topic_slug][$p->plan_slug] = $cell_content;
          $groups[$p->group]['col_order'][$p->plan_slug][$p->topic_slug] = $cell_content;
          $groups[$p->group]['rows_sorted'] = array();
          $groups[$p->group]['cols_sorted'] = array();
          ksort($groups[$p->group]['group_row_order']);
          ksort($groups[$p->group]['group_col_order']);

        }
        ksort($groups[$p->group]['group_post_order']);
        //$groups[$p->group][$p->group_order][$p->topic_slug][$p->plan_slug] = $cell_content;

        $groups[$p->group]['layout'] = $p->layout;
        $groups[$p->group]['premium_group'] = $p->premium_group;
      }

      // single layouts
      else
      {
        $slug = $p->post_name;
        $group = false;
        $formatted_content = str_replace(']]>',']]&gt>', apply_filters('the_content', $p->post_content));
        $singles[$p->post_name] = array(
          'layout' => $p->layout,
            'ID' => $p->ID,
          'title' => $p->post_title,
          'post_name' => $p->post_name,
            'post_excerpt' => $p->post_excerpt,
          'content' => $p->post_content, 
          'formatted_content' => $formatted_content,
            'thumb_url' => $p->thumb_url,
          'custom_content' => $p->custom_content,
          'custom_values' => $p->custom_values,
            'term_name' => $p->keyword,
            'term_slug' => $p->keyword_slug,
            'parent_name' => $p->parent_name,
            'parent_slug' => $p->parent_slug,
            'life_event' => $p->life_event,
            'life_event_name' => $p->life_event_name,
            'plan_features' => $p->plan_features
        );
      }
      $sort[$p->sort_order] = array('slug' => $slug, 'group' => $group);
    }

    // order sort array
    ksort($sort);

    // simplify group sort
    foreach($groups as &$current_group)
    {
      $srows = array();
      $scols = array();

      // CHART ONLY: check to see if group_heads and group_rows are set
      if(!empty($current_group['group_row_order']) || !empty($current_group['group_col_order'])) {
        // loop through row order and make single entry
        foreach($current_group['group_row_order'] as $sort_number )
        {
          foreach($sort_number as $key => $value)
          {
            //debug($value);
            if(!empty($value)) {
              $srows[$key] = $value;
            }
          }
        }

        $current_group['rows_sorted'] = $srows;


        // loop through col order and make single entry
        foreach($current_group['group_col_order'] as $sort_number_col )
        {
          foreach($sort_number_col as $ckey => $cvalue)
          {
            //debug($value);
            if(!empty($value)) {
              $scols[$ckey] = $cvalue;
            }
          }
        }

        $current_group['cols_sorted'] = $scols;

      }
    }

    //$qf_obj->posts = $posts;
    $qf_obj->sort_order = $sort;
    $qf_obj->groups = $groups;
    $qf_obj->singles = $singles;

    return $qf_obj;
  }

  /**
   * Gets wizard order and layout from wp_pc_pcomm_wizard_order table
   * @param  int $post_id ID of post
   * @param  int $term_id Term ID of keyword
   * @return object          Returns single row object from DB
   */
  public function get_qf_order($post_id, $term_id)
  {
    global $wpdb;

    $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';
  
    $order_layout = $wpdb->get_row( 
      "
      SELECT * 
      FROM $table
      WHERE post_id = $post_id
      AND term_id = $term_id
      "
    );

    return $order_layout;
  }

  public static function parameter_queryvars($qvars) 
  {
    $qvars[] = 'syn';
    return $qvars;
  }

  public static function get_synonym($syn)
  {
    global $wpdb;

    $table = $wpdb->prefix . 'pcomm_synonyms';
  
    $synonym = $wpdb->get_row( 
      "
      SELECT * 
      FROM $table
      WHERE synonym = '$syn'
      "
    );

    //debug($synonym);

    return $synonym;
  }

  public static function get_all_synonyms() {
    global $wpdb;
    $table = $wpdb->prefix . 'pcomm_synonyms';

    $syns = $wpdb->get_results(
        "
        SELECT *
        FROM $table
        "
    );

    return $syns;
  }


  /*******************************************************************************
   ** GETTERS & SETTERS **
   *******************************************************************************/
  

}

/* EOF */
?>
