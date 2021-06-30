<?php

/*
 * class PCQF_Admin
 * 
 * Creates admin settings page and admin methods for the QuickFind and Post Formats.
*/

class PCQF_Admin
{

    // Initializes plugin, similar to __construct()
    public static function init()
    {
        // Add the top-level menu
        add_action('admin_menu', 'PCQF_Admin::pcqf_create_menu');

        // Add the admin page
        //add_action('admin_menu', 'PCQF_Admin::pcqf_add_page');

        // Register settings
        add_action('admin_init', 'PCQF_Admin::pcqf_admin_init');

        // Update database if wizard tag is deleted
        //add_action( 'delete_term', 'PCQF_Admin::pcpw_update_term_on_delete' );

        // Update database if wizard tag is edited
        //add_action( 'edited_term', 'PCQF_Admin::pcpw_update_term_on_edit' );

        /*******************************************************************************
         ** POST FORMATS ACTIONS **
         *******************************************************************************/
        add_action('admin_menu', 'PCQF_Admin::pcqf_remove_meta_box');
        add_action('add_meta_boxes', 'PCQF_Admin::pcqf_post_format_add_meta_box');
    }

    /*******************************************************************************
     ** SCRIPTS AND STYLES **
     *******************************************************************************/
    public static function pcqf_admin_enqueue_styles()
    {
        wp_enqueue_style('pcqf-style-admin', plugins_url('../css/pcqf-admin.css', __FILE__), array(), '');
        wp_enqueue_style('jquery-ui-custom', plugins_url('../css/jquery-ui.min.css', __FILE__), array(), '');

    }

    public static function pcqf_admin_enqueue_scripts()
    {
        wp_enqueue_script('pcqf-js-admin', plugins_url('../js/pcqf-admin.js', __FILE__), array('jquery', 'jquery-ui-datepicker'));
        wp_localize_script('pcqf-js-admin', 'pcqf_ajax_admin', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    /*******************************************************************************
     ** ADD ADMIN PAGE AND FORMS **
     *******************************************************************************/
    // Add top-level menu item
    public static function pcqf_create_menu()
    {
        add_menu_page('Quick Find', 'Quick Find', 'manage_options', 'pcomm-quick-find', 'PCQF_Admin::pcqf_dashboard', 'dashicons-search');

        // create submenu items
        add_submenu_page('pcomm-quick-find', 'Keyword Manager', 'Keyword Manager', 'manage_options', 'keyword-manager', 'PCQF_Admin::pcqf_keyword_page');
        add_submenu_page('pcomm-quick-find', 'Keyword Taxonomy Manager', 'Keyword & Taxonomy Manager', 'manage_options', 'keyword-tax-manager', 'PCQF_Admin::pcqf_keyword_tax_page');
        add_submenu_page('pcomm-quick-find', 'Group Manager', 'Group Manager', 'manage_options', 'group-manager', 'PCQF_Admin::pcqf_group_page');


    }

    // Add admin page
    // public static function pcqf_add_page()
    // {
    //   add_options_page('PComm QuickFind Manager', 'PComm QuickFind Manager', 'manage_options', __FILE__, 'PCQF_Admin::pcqf_options_page');

    // }

    public static function pcqf_dashboard()
    {
        ?>

        <div class="wrap">
            <h2>PComm QuickFind Dashboard</h2>


        </div>

        <?php
    }

    // Options page render form
    public static function pcqf_keyword_page()
    {
        ?>

        <div class="wrap">
            <h2>Quick Find Keyword Manager</h2>
            <form action="options.php" method="post">
                <?php //settings_fields('pcqf_options');
                ?>
                <?php do_settings_sections(__FILE__ . '_keyword'); ?>
                <!-- <p class="submit"><input name="Submit" class="button-primary" type="submit" value="Get Posts" /></p> -->

            </form>


            <div id="pcqf_keyword_posts"></div>

        </div>

        <?php
    }

    // Options page render form
    public static function pcqf_keyword_tax_page()
    {
        ?>

        <div class="wrap">
            <h2>Quick Find Keyword &amp; Taxonomy Manager</h2>
            <form action="options.php" method="post">
                <?php //settings_fields('pcqf_options');
                ?>
                <?php do_settings_sections(__FILE__ . '_keyword_tax'); ?>
                <!-- <p class="submit"><input name="Submit" class="button-primary" type="submit" value="Get Posts" /></p> -->

            </form>


            <div id="pcqf_keyword_posts"></div>

        </div>

        <?php
    }

    // Options page render form
    public static function pcqf_group_page()
    {
        ?>

        <div class="wrap">
            <h2>Quick Find Group Manager</h2>
            <form action="options.php" method="post">
                <?php //settings_fields('pcqf_options');
                ?>
                <?php do_settings_sections(__FILE__ . '_group'); ?>
                <!-- <p class="submit"><input name="Submit" class="button-primary" type="submit" value="Get Posts" /></p> -->

            </form>


            <div id="pcqf_group_posts"></div>

        </div>

        <?php
    }

    // Define settings
    public static function pcqf_admin_init()
    {
        // group manager settings
        add_settings_section('pcqf_group_section', 'Group Sort Oder and Layout Manager', 'PCQF_Admin::pcqf_group_description_text', __FILE__ . '_group');
        add_settings_field('pcqf_groups', 'Select Group:', 'PCQF_Admin::pcqf_group_select', __FILE__ . '_group', 'pcqf_group_section');

        // keyword page settings
        add_settings_section('pcqf_keyword_section', 'Keyword Management', 'PCQF_Admin::pcqf_keyword_description_text', __FILE__ . '_keyword');
        add_settings_field('pcqf_keywords', 'Select Keyword:', 'PCQF_Admin::pcqf_keyword_select', __FILE__ . '_keyword', 'pcqf_keyword_section');

        // keyword taxonomy page settings
        add_settings_section('pcqf_keyword_section', 'Keyword and Taxonomy Management', 'PCQF_Admin::pcqf_keyword_tax_description_text', __FILE__ . '_keyword_tax');
        // location select
        add_settings_field('pcqf_location', 'Select Location:', 'PCQF_Admin::pcqf_location_select', __FILE__ . '_keyword_tax', 'pcqf_keyword_section');
        // salary select
        add_settings_field('pcqf_salary', 'Select Salary Band:', 'PCQF_Admin::pcqf_salary_select', __FILE__ . '_keyword_tax', 'pcqf_keyword_section');
        // payment schedule select
        add_settings_field('pcqf_payment_schedule', 'Select Payment Schedule:', 'PCQF_Admin::pcqf_payment_schedule_select', __FILE__ . '_keyword_tax', 'pcqf_keyword_section');
        // keyword select
        add_settings_field('pcqf_keywords', 'Select Keyword:', 'PCQF_Admin::pcqf_keyword_tax_select', __FILE__ . '_keyword_tax', 'pcqf_keyword_section');
    }

    public static function pcqf_group_description_text()
    {
        echo '<p>Select a group name to edit group layout and sort order.</p>';
    }

    // Text describing section called from register_settings()
    public static function pcqf_keyword_description_text()
    {
        echo '<p>Select a Keyword to view posts with selected keyword.</p>';
    }

    // Text describing section called from register_settings()
    public static function pcqf_keyword_tax_description_text()
    {
        echo '<p>Select a Location, Workgroup and Keyword to view posts with selected keyword.</p>';
    }

    public static function pcqf_get_keyword_groups_by_post($post_id)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        // Create SQL statement to get groups
        $sql = "
      SELECT *
      FROM $table
      WHERE post_id = $post_id
    ";

        $key_groups = $wpdb->get_results($sql);

        return $key_groups;
    }

    // Build select form element of keywords and parents
    public static function pcqf_group_select()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        // Create SQL statement to get groups
        $sql = "
      SELECT group_slug
      FROM $table
      WHERE group_slug != ''
      GROUP BY group_slug
    ";

        $groups = $wpdb->get_results($sql);

        // Build select tag
        $select = '<select id="pcqf_group_select" name="pcqf_groups">';

        $select .= '<option name="none" value="">-- CHOOSE A GROUP --</option>';

        foreach ($groups as $group) {

            $select .= '<option name="' . $group->group_slug . '" value="' . $group->group_slug . '">' . $group->group_slug . '</option>';
        }

        $select .= '</select>';


        echo $select;
    }

    public static function pcqf_get_group_select()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        // Create SQL statement to get groups
        $sql = "
      SELECT group_slug
      FROM $table
      WHERE group_slug != ''
      GROUP BY group_slug
    ";

        $groups = $wpdb->get_results($sql);

        // Build select tag
        $select = '<select class="pcqf_group_select" name="pcqf_groups">';

        $select .= '<option name="none" value="">-- CHOOSE A GROUP --</option>';

        foreach ($groups as $group) {

            $select .= '<option name="' . $group->group_slug . '" value="' . $group->group_slug . '">' . $group->group_slug . '</option>';
        }

        $select .= '</select>';

        return $select;
    }

    // Build select form element of keywords and parents
    public static function pcqf_keyword_select()
    {
        // get keyword terms Keyword taxonomy
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0
        );

        $terms = get_terms('keyword', $args);
        //debug($terms);

        // Build select tag
        $select = '<select id="pcqf_keyword_select" name="pcqf_keywords">';

        $select .= '<option name="none" value="">-- CHOOSE A KEYWORD --</option>';

        foreach ($terms as $term) {
            if ($term->parent) {
                $parent = get_term($term->parent, 'keyword');
                $value = $term->name . "|" . $term->term_id . "|" . $parent->name . "|" . $parent->term_id . "|" . $term->slug . "|" . $parent->slug;
                $label = $term->name . ' in ' . $parent->name;
            } else {
                $value = $term->name . "|" . $term->term_id . "|" . $term->name . "|" . $term->term_id . "|" . $term->slug . "|" . $term->slug;
                $label = $term->name;
            }

            $select .= '<option name="' . $term->slug . '" value="' . $value . '">' . $label . '</option>';
        }

        $select .= '</select>';


        echo $select;
    }

    // Build select form element of keywords and parents
    public static function pcqf_keyword_tax_select()
    {
        // get keyword terms Keyword taxonomy
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0
        );

        $terms = get_terms('keyword', $args);
        //debug($terms);

        // Build select tag
        $select = '<select id="pcqf_keyword_tax_select" name="pcqf_tax_keywords">';

        $select .= '<option name="none" value="">-- CHOOSE A KEYWORD --</option>';

        foreach ($terms as $term) {
            if ($term->parent) {
                $parent = get_term($term->parent, 'keyword');
                $value = $term->name . "|" . $term->term_id . "|" . $parent->name . "|" . $parent->term_id . "|" . $term->slug . "|" . $parent->slug;
                $label = $term->name . ' in ' . $parent->name;
            } else {
                $value = $term->name . "|" . $term->term_id . "|" . $term->name . "|" . $term->term_id . "|" . $term->slug . "|" . $term->slug;
                $label = $term->name;
            }

            $select .= '<option name="' . $term->slug . '" value="' . $value . '">' . $label . '</option>';
        }

        $select .= '</select>';


        echo $select;
    }

    // Build select form location terms
    public static function pcqf_location_select()
    {
        // get keyword terms location taxonomy
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0
        );

        $terms = get_terms('location', $args);
        //debug($terms);

        // Build select tag
        $select = '<select id="pcqf_location_select" name="pcqf_locations">';

        $select .= '<option name="none" value="">-- CHOOSE A LOCATION --</option>';

        foreach ($terms as $term) {
            if ($term->slug != 'all-locations') {
                $select .= '<option name="' . $term->slug . '" value="' . $term->slug . '">' . $term->name . '</option>';
            }
        }

        $select .= '</select>';


        echo $select;
    }

    // Build select form salary band terms
    public static function pcqf_salary_select()
    {
        // get keyword terms salary band taxonomy
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0
        );

        $terms = get_terms('salary_band', $args);
        //debug($terms);

        // Build select tag
        $select = '<select id="pcqf_salary_select" name="pcqf_salary">';

        $select .= '<option name="none" value="">-- CHOOSE A SALARY BAND --</option>';

        foreach ($terms as $term) {
            if ($term->slug != 'all-salary-bands') {
                $select .= '<option name="' . $term->slug . '" value="' . $term->slug . '">' . $term->name . '</option>';
            }
        }

        $select .= '</select>';

        echo $select;
    }

    // Build select form payment schedule terms
    public static function pcqf_payment_schedule_select()
    {
        // get keyword terms coverage taxonomy
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0
        );

        $terms = get_terms('payment_schedule', $args);
        //debug($terms);

        // Build select tag
        $select = '<select id="pcqf_payment_schedule_select" name="pcqf_payment_schedule">';

        $select .= '<option name="none" value="">-- CHOOSE A PAYMENT SCHEDULE --</option>';

        foreach ($terms as $term) {
            if ($term->slug != 'all-payment-schedules') {
                $select .= '<option name="' . $term->slug . '" value="' . $term->slug . '">' . $term->name . '</option>';
            }
        }

        $select .= '</select>';

        echo $select;
    }

    // Build select form workgroup terms
    public static function pcqf_workgroup_select()
    {
        // get keyword terms workgroup taxonomy
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => 0
        );

        $terms = get_terms('workgroup', $args);
        //debug($terms);

        // Build select tag
        $select = '<select id="pcqf_workgroup_select" name="pcqf_workgroups">';

        $select .= '<option name="none" value="">-- CHOOSE A WORKGROUP --</option>';

        foreach ($terms as $term) {
            if ($term->slug != 'all-workgroups') {
                $select .= '<option name="' . $term->slug . '" value="' . $term->slug . '">' . $term->name . '</option>';
            }

        }

        $select .= '</select>';


        echo $select;
    }

    public static function pcqf_get_keyword_select()
    {
        // get keyword terms Keyword taxonomy
        $args = array(
            'taxonomy' => 'keyword',
            'order' => 'ASC',
            'orderby' => 'slug',
            'hide_empty' => 0
        );

        $terms = get_terms('keyword', $args);
        //debug($terms);

        // Build select tag
        $select = '<select id="pcqf_keyword_select" name="pcqf_keywords">';

        $select .= '<option name="none" value="">-- CHOOSE A KEYWORD --</option>';

        foreach ($terms as $term) {
            if ($term->parent) {
                $parent = get_term($term->parent, 'keyword');
                $value = $term->name . "|" . $term->term_id . "|" . $parent->name . "|" . $parent->term_id . "|" . $term->slug . "|" . $parent->slug;
                $label = $term->name . ' in ' . $parent->name;
            } else {
                $value = $term->name . "|" . $term->term_id . "|" . $term->name . "|" . $term->term_id . "|" . $term->slug . "|" . $term->slug;
                $label = $term->name;
            }

            $select .= '<option name="' . $term->slug . '" value="' . $value . '">' . $label . '</option>';
        }

        $select .= '</select>';

        return $select;
    }

    /*******************************************************************************
     ** AJAX METHODS FOR ADMIN **
     *******************************************************************************/

    /*
      Ajax method to get all posts by group
    */
    public static function pcqf_get_posts_by_group()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        $selected_group = $_POST['sel_group'];
        //debug($selected_group);

        $sql =
            "
      SELECT * 
      FROM $table
      WHERE group_slug = '$selected_group'
      ORDER BY group_order ASC
      ";

        $groups = $wpdb->get_results($sql);

        //debug($groups);

        $col_heads = array('Post Title', 'Keyword', 'Group', 'Group Order', 'Group Layout');

        $table = '<table class="widefat">';

        $table .= '<thead><tr>';

        foreach ($col_heads as $head) {
            $table .= '<td class="col_head">' . $head . '</td>';
        }

        $table .= '</tr></thead>';

        // tbody
        $table .= '<tbody>';


        foreach ($groups as $group) {
            $post_title = get_post_field('post_title', $group->post_id);
            $post_title = str_replace('<!--', '&lt;!--', $post_title);
            $post_title = str_replace('-->', '--&gt;', $post_title);
            // Add rows of set posts to table sorted by sort_order and group_order
            $table .= self::pcqf_get_group_row($post_title, $group);
        }

        $table .= '</tbody></table>';

        die($table);

    }


    /*
     Ajax method to get all posts by selected keyword
     */
    public static function pcqf_get_posts_by_keyword()
    {
        $selected_term = explode("|", $_POST['sel_term']);

        $term_info = array(
            'term_name' => $selected_term[0],
            'term_id' => $selected_term[1],
            'parent_name' => $selected_term[2],
            'parent_id' => $selected_term[3],
            'term_slug' => $selected_term[4],
            'parent_slug' => $selected_term[5]
        );

        $keyword_query = new WP_Query(array(
            // all posts that match
            'posts_per_page' => -1,
            'post_status' => 'publish',
            // only Plan Detail content types
            'post_type' => array(
                'post',
                'medical-type',
                'dental-vision-type',
                'accounts-type',
                'retirement-type',
                'wellness-type',
                'life-event-type',
                'other-type',
                'income-type',
                'contact',
                'docs-type',
            ),
            // limit to selected keyword taxonomy
            'tax_query' => array(
                array(
                    'taxonomy' => 'keyword',
                    'field' => 'slug',
                    'terms' => $term_info['term_slug']
                )
            )
        ));

        $col_heads = array('Post Title', 'Keyword', 'Sort Order', 'Layout', 'Group', 'Group Order', 'Group Layout');

        $table = '<table class="widefat">';

        $table .= '<thead><tr>';

        foreach ($col_heads as $head) {
            $table .= '<td class="col_head">' . $head . '</td>';
        }

        $table .= '</tr></thead>';

        // tbody
        $table .= '<tbody>';

        // create arrays for finding set posts
        $pids = array();
        $ptitles = array();

        // Get all post ids from query and push to array
        foreach ($keyword_query->posts as $pid) {
            array_push($pids, $pid->ID);
            $post_title = $pid->post_title;
            $post_title = str_replace('<!--', '&lt;!--', $post_title);
            $post_title = str_replace('-->', '--&gt;', $post_title);
            $ptitles[$pid->ID]['post_title'] = $post_title;
        }

        // Get all posts with matching IDs from order_layout table
        $current_posts = self::pcqf_get_order_layout_by_ids($pids, $term_info['term_id']);

        // Set array of IDs from $current_posts query
        $cpids = array();

        foreach ($current_posts as $cp) {
            // Add rows of set posts to table sorted by sort_order and group_order
            $table .= self::pcqf_get_set_row($ptitles[$cp->post_id]['post_title'], $cp);
            array_push($cpids, $cp->post_id);
        }

        // loop through all posts and add rows of posts not found in order_layout table or aren't set
        foreach ($keyword_query->posts as $p) {
            if (!in_array($p->ID, $cpids)) {
                // Add rows to table of not set posts
                $table .= self::pcqf_get_not_set_row($p->post_title, $p->ID, $term_info);
            }

        }

        $table .= '</tbody></table>';

        die($table);
    }

    /*
    Ajax method to get all posts by selected keyword and location and workgroup
    */
    public static function pcqf_get_posts_by_keyword_taxonomy()
    {

        $selected_term = explode("|", $_POST['sel_term']);
        $selected_location = $_POST['sel_location'];
        $selected_salary_band = $_POST['sel_salary'];
        $selected_payment_schedule = $_POST['sel_payment_schedule'];

        // set up terms arrays
        if (!empty($selected_location)) {
            $location_terms = array('all-locations', $selected_location);
        } else {
            $location_terms = array('all-locations');
        }

        if (!empty($selected_salary_band)) {
            $salary_band_terms = array('all-salary-bands', $selected_salary_band);
        } else {
            $salary_band_terms = array('all-salary-bands');
        }

        if (!empty($selected_payment_schedule)) {
            $payment_schedule_terms = array('all-payment-schedules', $selected_payment_schedule);
        } else {
            $payment_schedule_terms = array('all-payment-schedules');
        }


        $term_info = array(
            'term_name' => $selected_term[0],
            'term_id' => $selected_term[1],
            'parent_name' => $selected_term[2],
            'parent_id' => $selected_term[3],
            'term_slug' => $selected_term[4],
            'parent_slug' => $selected_term[5]
        );

        $keyword_query = new WP_Query(array(
            // all posts that match
            'posts_per_page' => -1,
            'post_status' => 'publish',
            // only Plan Detail content types
            'post_type' => array(
                'post',
                'medical-type',
                'dental-vision-type',
                'accounts-type',
                'retirement-type',
                'wellness-type',
                'life-event-type',
                'other-type',
                'income-type',
                'contact',
                'docs-type',
            ),
            // limit to selected keyword taxonomy
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'keyword',
                    'field' => 'slug',
                    'terms' => $term_info['term_slug']
                ),
                array(
                    'taxonomy' => 'location',
                    'field' => 'slug',
                    'terms' => $location_terms
                ),
                array(
                    'taxonomy' => 'salary_band',
                    'field' => 'slug',
                    'terms' => $salary_band_terms
                ),
                array(
                    'taxonomy' => 'payment_schedule',
                    'field' => 'slug',
                    'terms' => $payment_schedule_terms
                )

            )
        ));

        $col_heads = array('Post Title', 'Keyword', 'Sort Order', 'Layout', 'Group', 'Group Order', 'Group Layout');

        $table = '<table class="widefat">';

        $table .= '<thead><tr>';

        foreach ($col_heads as $head) {
            $table .= '<td class="col_head">' . $head . '</td>';
        }

        $table .= '</tr></thead>';

        // tbody
        $table .= '<tbody>';

        // create arrays for finding set posts
        $pids = array();
        $ptitles = array();

        // Get all post ids from query and push to array
        foreach ($keyword_query->posts as $pid) {
            array_push($pids, $pid->ID);
            $post_title = $pid->post_title;
            $post_title = str_replace('<!--', '&lt;!--', $post_title);
            $post_title = str_replace('-->', '--&gt;', $post_title);
            $ptitles[$pid->ID]['post_title'] = $post_title;
        }

        // Get all posts with matching IDs from order_layout table
        $current_posts = self::pcqf_get_order_layout_by_ids($pids, $term_info['term_id']);

        // Set array of IDs from $current_posts query
        $cpids = array();

        foreach ($current_posts as $cp) {
            // Add rows of set posts to table sorted by sort_order and group_order
            $table .= self::pcqf_get_set_row($ptitles[$cp->post_id]['post_title'], $cp);
            array_push($cpids, $cp->post_id);
        }

        // loop through all posts and add rows of posts not found in order_layout table or aren't set
        foreach ($keyword_query->posts as $p) {
            if (!in_array($p->ID, $cpids)) {
                // Add rows to table of not set posts
                $table .= self::pcqf_get_not_set_row($p->post_title, $p->ID, $term_info);
            }

        }

        $table .= '</tbody></table>';

        die($table);
    }

    public static function pcqf_get_edit_group_post_form()
    {
        $current_post = get_post($_POST['post_id'], OBJECT, 'edit');
        $current_group = self::pcqf_get_order_layout($_POST['post_id'], $_POST['term_id']);

        //debug($current_post);

        $content = $current_post->post_content;
        $current_post->formatted_content = str_replace(']]>', ']]&gt>', apply_filters('the_content', $current_post->post_content));
        $editor_id = 'editgrouppost';

        // Taxonomies
        //$taxonomy_names = get_object_taxonomies( $current_post->post_type );
        $taxonomy_names = array('group_head', 'group_row');
        $hierarchical_taxonomies = array();
        $flat_taxonomies = array();
        foreach ($taxonomy_names as $taxonomy_name) {
            $taxonomy = get_taxonomy($taxonomy_name);

            if (!$taxonomy->show_ui)
                continue;

            if ($taxonomy->hierarchical)
                $hierarchical_taxonomies[] = $taxonomy;
            else
                $flat_taxonomies[] = $taxonomy;
        }
        //debug($taxonomy_names);
        $post_title = $current_post->post_title;
        $post_title = str_replace('<!--', '&lt;!--', $post_title);
        $post_title = str_replace('-->', '--&gt;', $post_title);

        // form using update method
        $table = '<tr class="group_update_row"><td style="width: 100%; padding: 0; margin: 0;" colspan="5"><form method="post" class="update_group_post" action=""><table style="width: 100%;"><tbody id="groupedit">';
        $table .= '<tr class="inline-edit-row inline-edit-row-post quick-edit-row quick-edit-row-post inline-edit-post alternate inline-editor"><td>';

        // left column
        $table .= '<fieldset class="inline-edit-col-left"><div class="inline-edit-col">';
        $table .= '<h4>' . $post_title . '</h4>';

        $table .= '<label><span class="title">Group</span>';
        $table .= '<span class="input-text-wrap">' . $current_group->group_slug . '</span></label>';

        $table .= '<label><span class="title">Keyword</span>';
        $table .= '<span class="input-text-wrap">' . $current_group->term_name . '</span></label>';

        $table .= '<label style="clear:both;"><span class="title">Order</span>';
        $table .= '<span class="input-text-wrap"><input type="text" maxlength="3" size="3" value ="' . $current_group->group_order . '" name="group_order" />';
        $table .= '</span></label>';

        $table .= '<label style="clear: both;"><span class="title">Layout</span>';
        $table .= '<span class="input-text-wrap">';
        $table .= self::pcqf_get_layout_select($current_group->group_layout, 'group_layout');
        $table .= '</span></label>';

        // $table .= '<label style="clear:both;"><span class="title">Other Groups</span></label>';
        // $table .= '<div class="keywords_groups"><table class="keyword_group_table"><thead><tr><td>Keyword</td><td>Group</td><td>Edit</td></tr></thead>';
        // $table .= '<tbody>';

        // $others = self::pcqf_get_keyword_groups_by_post($current_post->ID);

        // foreach ($others as $other) {
        //   $table .= '<tr class="keyword_group_row"
        //             data-qfid="' . $other->qfid . '" ' .
        //             'data-postid="' . $other->post_id . '" ' .
        //             'data-termid="' . $other->term_id . '" ' .
        //             'data-termname="' . $other->term_name . '" ' .
        //             'data-termslug="' . $other->term_slug . '" ' .
        //             'data-groupslug="' . $other->group_slug . '" ' .
        //             'data-action="pcqf_delete_group_keyword_by_ajax" >';
        //   $table .= '<td class="other_keyword">' . $other->term_name . '</td>';
        //   $table .= '<td class="other_group">' . $other->group_slug . '</td>';
        //   $table .= '<td class="delete_group_keyword"><a href="">Delete</a></td></tr>';
        // }

        // $table .= '<tr><td class="add_group_keyword" colspan="3" data-postid="' . $current_post->ID . '" data-action="pcqf_get_add_group_keyword_form"><a href="">Add new keyword/group</a></td></tr>';
        // $table .= '</tbody></table></div>';

        // end left column
        $table .= '</div></fieldset>';

        // center column
        $table .= '<fieldset class="inline-edit-col-center inline-edit-categories"><div class="inline-edit-col">';

        // loop through taxonomies
        foreach ($hierarchical_taxonomies as $taxonomy) {
            $table .= '<span class="title inline-edit-categories-label">' . esc_html($taxonomy->labels->name) . '</span>';
            $tax_name = ($taxonomy->name == 'category') ? 'post_category[]' : 'tax_input[' . esc_attr($taxonomy->name) . '][]';
            $table .= '<input type="hidden" name="' . $tax_name . '" value="0" />';
            $table .= '<ul class="cat-checklist ' . esc_attr($taxonomy->name) . '-checklist">';
            ob_start();
            wp_terms_checklist($current_post->ID, array('taxonomy' => $taxonomy->name));
            $res = ob_get_contents();
            ob_end_clean();

            $table .= $res;
            $table .= '</ul>';
        }

        // end center column
        $table .= '</div></fieldset>';

        // right column
        $table .= '<fieldset class="inline-edit-col-right"><div class="inline-edit-col">';
        $table .= '<label class="inline-edit-tags"><span class="title">Content</span>';
        $table .= '<textarea class="tax_input_post_tag" name="post_content" rows="1" cols="22" style="height:180px;">' . $current_post->post_content . '</textarea>';
        $table .= '</label>';

        // ob_start();
        // wp_editor($content, $editor_id);
        // $edit = ob_get_contents();
        // ob_end_clean();

        // $table .= $edit;

        // end right column
        $table .= '</div></fieldset>';

        $table .= '<input type="hidden" name="qfid" value="' . $current_group->qfid . '" />';
        $table .= '<input type="hidden" name="post_id" value="' . $current_post->ID . '" />';
        $table .= '<input type="hidden" name="group" value ="' . $current_group->group_slug . '" />';
        $table .= '<input type="hidden" name="term_id" value="' . $current_group->term_id . '" />';
        $table .= '<input type="hidden" name="term_name" value="' . $current_group->term_name . '" />';
        $table .= '<input type="hidden" name="term_slug" value="' . $current_group->term_slug . '" />';
        $table .= '<input type="hidden" name="post_title" value="' . $post_title . '" />';
        $table .= '<input type="hidden" name="action" value="pcqf_update_group_post_settings" />';

        // closing table/form in row
        $table .= '<p class="submit"><input class="button-primary alignright" type="submit" value="Update Group Post" /></p><p>&nbsp;</p>';
        $table .= '</td></tr></tbody></table></form></td></tr>';

        die($table);
    }

    public static function pcqf_get_add_group_keyword_form()
    {
        $add_form = '<tr class="add_group_keyword_row"><td colspan="3"><form method="post" class="add_group_keyword_form" action=""><fieldset>';
        $add_form .= '<label style="clear: both;"><span class="title">Group</span>';
        $add_form .= '<span class="input-text-wrap">';
        $add_form .= self::pcqf_get_group_select();
        $add_form .= '</span></label>';
        $add_form .= '<label><span class="title">New Group</span>';
        $add_form .= '<span class="input-text-wrap"><input type="text" value ="" name="group" />';
        $add_form .= '</span></label>';
        $add_form .= '<label style="clear: both;"><span class="title">Keyword</span>';
        $add_form .= '<span class="input-text-wrap">';
        $add_form .= self::pcqf_get_keyword_select();
        $add_form .= '</span></label>';
        $add_form .= '<input type="hidden" name="post_id" value="' . $_POST['postid'] . '" />';
        $add_form .= '<input type="hidden" name="action" value="pcqf_add_group_keyword_by_ajax" />';
        $add_form .= '<p class="submit"><input class="button-primary alignright" type="submit" value="Add New Group/Keyword" /></p>';

        $add_form .= '</fieldset></form></td></tr>';

        die($add_form);
    }

    public static function pcqf_add_group_keyword_by_ajax()
    {
        debug($_POST);

        die();
    }

    public static function pcqf_delete_group_keyword_by_ajax()
    {
        $post_id = $_POST['postid'];
        $group_slug = $_POST['groupslug'];
        $term_id = $_POST['termid'];

        $res = self::pcqf_delete_group_keyword($post_id, $group_slug, $term_id);

        die($res);
    }

    public static function pcqf_update_group_post_settings()
    {
        //debug($_POST);
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        $post_arr = array(
            'post_content' => $_POST['post_content'],
            'ID' => $_POST['post_id']
        );

        $post_update = wp_update_post($post_arr);

        if ($post_update != 0) {
            $post_title = get_post_field('post_title', $_POST['post_id']);
            $post_title = str_replace('<!--', '&lt;!--', $post_title);
            $post_title = str_replace('-->', '--&gt;', $post_title);

            // update group settings after post update
            $data = array(
                'post_id' => $_POST['post_id'],
                'term_id' => $_POST['term_id'],
                'term_name' => $_POST['term_name'],
                'term_slug' => $_POST['term_slug'],
                'group_slug' => $_POST['group'],
                'group_order' => $_POST['group_order'],
                'group_layout' => $_POST['group_layout']
            );

            //update query
            $format = array('%d', '%d', '%s', '%s', '%s', '%d', '%s');

            $where = array('qfid' => $_POST['qfid']);

            $res = $wpdb->update($table, $data, $where, $format, array('%d', '%s'));

            if ($res > 0) {
                $new_settings = self::pcqf_get_order_layout_by_id($_POST['qfid']);
            } else {
                $new_settings = self::pcqf_get_order_layout_by_id($_POST['qfid']);
            }

            $tr = self::pcqf_get_group_row($post_title, $new_settings);

        } else {
            $tr = '<tr><td>ERROR: POST FAILED TO UPDATE.</td></tr>';
        }

        die($tr);

    }

    public static function pcqf_get_update_form()
    {

        $current_settings = self::pcqf_get_order_layout($_POST['post_id'], $_POST['term_id']);

        // form using update method
        $table = '<tr class="keyword_update_row inline-edit-row quick-edit-row quick-edit-row-post inline-edit-post alternate inline-editor"><td class="colspanchange" colspan="7"><form class="update_keyword_order" action="" method="post">';
        $table .= '<fieldset class="inline-edit-col-left"><div class="inline_edit_col"><h4>' . stripslashes($_POST['post_title']) . '</h4>';
        $table .= '<label><span class="title">Sort Order</span>';
        $table .= '<span class=""><input type="text" maxlength="3" size="3" value ="' . $current_settings->sort_order . '" name="sort_order" />';
        $table .= '</span></label>';

        $table .= '<label><span class="title">Layout</span>';
        $table .= '<span class="input-text-wrap">';
        $table .= self::pcqf_get_layout_select($current_settings->layout, 'layout');
        $table .= '</span></label>';

        $table .= '<label><span class="title">Group</span>';
        $table .= '<span class="input-text-wrap"><input type="text" value ="' . $current_settings->group_slug . '" name="group" />';
        $table .= '</span></label>';

        $table .= '<label><span class="title">Group Order</span>';
        $table .= '<span class=""><input type="text" maxlength="3" size="3" value ="' . $current_settings->group_order . '" name="group_order" />';
        $table .= '</span></label>';

        $table .= '<label><span class="title">Group Layout</span>';
        $table .= '<span class="input-text-wrap">';
        $table .= self::pcqf_get_layout_select($current_settings->group_layout, 'group_layout');
        $table .= '</span></label>';

        $table .= '<input type="hidden" name="qfid" value="' . $current_settings->qfid . '" />';
        $table .= '<input type="hidden" name="post_id" value="' . $_POST['post_id'] . '" />';
        $table .= '<input type="hidden" name="term_id" value="' . $_POST['term_id'] . '" />';
        $table .= '<input type="hidden" name="term_name" value="' . stripslashes($_POST['term_name']) . '" />';
        $table .= '<input type="hidden" name="term_slug" value="' . stripslashes($_POST['term_slug']) . '" />';
        $table .= '<input type="hidden" name="post_title" value="' . stripslashes($_POST['post_title']) . '" />';
        $table .= '<input type="hidden" name="action" value="pcqf_update_keyword_settings" />';

        $table .= '</div></fieldset>';
        $table .= '<p class="submit">
                <input class="button-primary alignright" type="submit" value="Update Post Settings" />
                <a style="margin: 0 1em;" class="button-secondary alignright" href="/core/wp-admin/post.php?post=' . $_POST['post_id'] . '&action=edit" target="_blank">Edit Post in New Tab</a>
               </p>';
        $table .= '<p>&nbsp;</p></form></td></tr>';

        die($table);
    }

    public static function pcqf_update_keyword_settings()
    {
        global $wpdb;

        $data = array(
            'post_id' => $_POST['post_id'],
            'term_id' => $_POST['term_id'],
            'term_name' => $_POST['term_name'],
            'term_slug' => $_POST['term_slug'],
            'sort_order' => $_POST['sort_order'],
            'group_slug' => $_POST['group'],
            'group_order' => $_POST['group_order'],
            'layout' => $_POST['layout'],
            'group_layout' => $_POST['group_layout']
        );

        //debug($data);

        foreach ($data as $k => $v) {
            $data[$k] = stripslashes_deep($v);
        }

        $format = array('%d', '%d', '%s', '%s', '%d', '%s', '%d', '%s', '%s');

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        if ($_POST['qfid'] > 0) {
            $where = array('qfid' => $_POST['qfid']);

            $res = $wpdb->update($table, $data, $where, $format, array('%d'));
            if ($res > 0) {
                $new_settings = self::pcqf_get_order_layout_by_id($_POST['qfid']);
            } else {
                $new_settings = self::pcqf_get_order_layout_by_id($_POST['qfid']);
            }
        } else {
            $wpdb->insert($table, $data, $format);
            if ($wpdb->insert_id > 0) {
                $new_settings = self::pcqf_get_order_layout_by_id($wpdb->insert_id);
            }
        }

        $new_row = self::pcqf_get_set_row($_POST['post_title'], $new_settings);

        die($new_row);
    }

    private static function pcqf_get_group_row($post_title, $current_settings)
    {
        $post_title = str_replace('<!--', '&lt;!--', $post_title);
        $post_title = str_replace('-->', '--&gt;', $post_title);
        $table_cells = '<tr class="set_row group_row">';
        $table_cells .= '<td><strong>' . stripslashes($post_title) . '</strong><div class="row_actions>';
        $table_cells .= '<span class="edit_group><form class="update_group_form" method="post" action="">';
        $table_cells .= '<input type="hidden" name="post_id" value="' . $current_settings->post_id . '" />';
        $table_cells .= '<input type="hidden" name="term_id" value="' . $current_settings->term_id . '" />';
        $table_cells .= '<input type="hidden" name="term_slug" value="' . $current_settings->term_slug . '" />';
        $table_cells .= '<input type="hidden" name="term_name" value="' . stripslashes($current_settings->term_name) . '" />';
        $table_cells .= '<input type="hidden" name="post_title" value="' . stripslashes($post_title) . '" />';
        $table_cells .= '<input type="hidden" name="action" value="pcqf_get_edit_group_post_form" />';
        $table_cells .= '<p><input class="button-primary" type="submit" value="Update Post Settings" /></p>';
        $table_cells .= '</form></span></div></td>';
        $table_cells .= '<td class="keyword_term_name">' . $current_settings->term_name . '</td>';
        $table_cells .= '<td class="keyword_group">' . $current_settings->group_slug . '</td>';
        $table_cells .= '<td class="keyword_group_order">' . $current_settings->group_order . '</td>';
        $table_cells .= '<td class="keyword_group_layout">' . $current_settings->group_layout . '</td>';
        $table_cells .= '</tr>';

        return $table_cells;
    }

    private static function pcqf_get_set_row($post_title, $current_settings)
    {
        $post_title = str_replace('<!--', '&lt;!--', $post_title);
        $post_title = str_replace('-->', '--&gt;', $post_title);
        $table_cells = '<tr class="set_row keyword_row">';
        $table_cells .= '<td><strong>' . stripslashes($post_title) . '</strong><div class="row_actions">';
        $table_cells .= '<span class="edit_keyword"><form class="update_form" method="post" action="">';
        $table_cells .= '<input type="hidden" name="post_id" value="' . $current_settings->post_id . '" />';
        $table_cells .= '<input type="hidden" name="term_id" value="' . $current_settings->term_id . '" />';
        $table_cells .= '<input type="hidden" name="term_slug" value="' . $current_settings->term_slug . '" />';
        $table_cells .= '<input type="hidden" name="term_name" value="' . stripslashes($current_settings->term_name) . '" />';
        $table_cells .= '<input type="hidden" name="post_title" value="' . stripslashes($post_title) . '" />';
        $table_cells .= '<input type="hidden" name="action" value="pcqf_get_update_form" />';
        $table_cells .= '<p><input class="button-primary" type="submit" value="Update Post Settings" /></p>';
        $table_cells .= '</form></span></div></td>';
        $table_cells .= '<td class="keyword_term_name">' . $current_settings->term_name . '</td>';
        $table_cells .= '<td class="keyword_sort_order">' . $current_settings->sort_order . '</td>';
        $table_cells .= '<td class="keyword_layout">' . $current_settings->layout . '</td>';
        $table_cells .= '<td class="keyword_group">' . $current_settings->group_slug . '</td>';
        $table_cells .= '<td class="keyword_group_order">' . $current_settings->group_order . '</td>';
        $table_cells .= '<td class="keyword_group_layout">' . $current_settings->group_layout . '</td>';
        $table_cells .= '</tr>';

        return $table_cells;
    }

    private static function pcqf_get_not_set_row($post_title, $post_id, $term_info)
    {
        $post_title = str_replace('<!--', '&lt;!--', $post_title);
        $post_title = str_replace('-->', '--&gt;', $post_title);
        $table_cells = '<tr class="not_set_row keyword_row">';
        $table_cells .= '<td><strong>' . stripslashes($post_title) . '</strong><div class="row_actions>';
        $table_cells .= '<span class="edit_keyword><form class="update_form" method="post" action="">';
        $table_cells .= '<input type="hidden" name="post_id" value="' . $post_id . '" />';
        $table_cells .= '<input type="hidden" name="term_id" value="' . $term_info['term_id'] . '" />';
        $table_cells .= '<input type="hidden" name="term_slug" value="' . $term_info['term_slug'] . '" />';
        $table_cells .= '<input type="hidden" name="term_name" value="' . stripslashes($term_info['term_name']) . '" />';
        $table_cells .= '<input type="hidden" name="post_title" value="' . stripslashes($post_title) . '" />';
        $table_cells .= '<input type="hidden" name="action" value="pcqf_get_update_form" />';
        $table_cells .= '<p><input class="button-primary" type="submit" value="Update Post Settings" /></p>';
        $table_cells .= '</form></span></div></td>';
        $table_cells .= '<td class="keyword_term_name">' . $term_info['term_name'] . '</td>';
        $table_cells .= '<td class="keyword_sort_order">' . 'Not Set' . '</td>';
        $table_cells .= '<td class="keyword_layout">' . 'Not Set' . '</td>';
        $table_cells .= '<td class="keyword_group">' . 'Not Set' . '</td>';
        $table_cells .= '<td class="keyword_group_order">' . 'Not Set' . '</td>';
        $table_cells .= '<td class="keyword_group_layout">' . 'Not Set' . '</td>';
        $table_cells .= '</tr>';

        return $table_cells;
    }

    private static function pcqf_get_layout_select($selected_value = null, $name)
    {
        // get post format taxonomy
        $args = array(
            'taxonomy' => 'quickfind_view',
            'order' => 'ASC',
            'orderby' => 'name',
            'hide_empty' => 0
        );

        $terms = get_terms('quickfind_view', $args);
        //debug($terms);

        // Build select tag
        $select = '<select id="keyword_format_select" name="' . $name . '">';

        $select .= '<option name="none" value="">-- CHOOSE A LAYOUT --</option>';

        foreach ($terms as $term) {
            $value = $term->slug;
            $label = $term->name;

            if ($term->slug == $selected_value) {
                $select .= '<option name="' . $term->slug . '" value="' . $value . '" selected>' . $label . '</option>';
            } else {
                $select .= '<option name="' . $term->slug . '" value="' . $value . '">' . $label . '</option>';
            }

        }

        $select .= '</select>';


        return $select;
    }

    private static function pcqf_get_order_layout_by_ids($id_array, $term_id)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        // Create SQL statement using IDs of set posts
        $sql = "
      SELECT *
      FROM $table
      WHERE post_id IN(" . implode(', ', $id_array) . ")
      AND term_id = $term_id
      ORDER BY sort_order, group_order
    ";

        $set_posts = $wpdb->get_results($sql);

        return $set_posts;
    }

    private static function pcqf_get_order_layout($post_id, $term_id)
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

    private static function pcqf_get_order_layout_by_id($qfid)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        $order_layout = $wpdb->get_row(
            "
      SELECT * 
      FROM $table
      WHERE qfid = $qfid
      "
        );

        return $order_layout;
    }

    public static function pcqf_delete_group_keyword($post_id, $group_slug, $term_id)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        $args = array($post_id, $group_slug, $term_id);

        $res = $wpdb->query(
            $wpdb->prepare(
                "
        DELETE FROM $table
        WHERE post_id = %d
        AND group_slug = %s
        AND term_id = %d
        ",
                $args
            )
        );

        return $res;
    }

    // update database when wizard tag is deleted from WP
    public static function pcqf_update_term_on_delete($term, $tt_id, $taxonomy)
    {
        global $wpdb;

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        $wpdb->query(
            $wpdb->prepare(
                "
        DELETE FROM $table
        WHERE term_id = %d
        ",
                $term
            )
        );
    }

    // update database when wizard tag is deleted from WP
    public static function pcqf_update_term_on_edit($term_id, $tt_id, $taxonomy)
    {
        global $wpdb;

        $edited_term = get_term($term_id, 'keyword');

        $table = $wpdb->prefix . 'pcomm_quickfind_order_layout';

        $args = array($edited_term->slug, $edited_term->name, $term_id);

        $wpdb->query(
            $wpdb->prepare(
                "
        UPDATE $table
        SET term_slug = %s, term_name = %s
        WHERE term_id = %d
        ",
                $args
            )
        );
    }

    /*******************************************************************************
     ** POST FORMAT ADMIN METHODS **
     *******************************************************************************/

    // http://wp.tutsplus.com/tutorials/creative-coding/how-to-use-radio-buttons-with-taxonomies/

    /**
     * Reomoves sidebar metabox
     */
    public static function pcqf_remove_meta_box()
    {
        remove_meta_box('post_formatdiv', 'cost', 'side');
    }

    /**
     * Adds new post format metabox with radio buttons
     */
    public static function pcqf_post_format_add_meta_box()
    {
        add_meta_box('post_format', 'Set Post Format', 'PCQF_Admin::pcqf_post_format_metabox', 'cost', 'normal', 'core');
    }

    /**
     * Callback to set up the metabox from previous method
     * @param  object $post Current post
     * @return string       HTML for displaying meta box
     */
    public static function pcqf_post_format_metabox($post)
    {
        //Get taxonomy and terms
        $taxonomy = 'pcomm_post_format';
        $default_format = 'Chart';
        //Set up the taxonomy object and get terms
        $tax = get_taxonomy($taxonomy);
        $terms = get_terms($taxonomy, array('hide_empty' => 0));

        //Name of the form
        $name = 'tax_input[' . $taxonomy . ']';

        //Get current and popular terms
        $popular = get_terms($taxonomy, array('orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false));
        $postterms = get_the_terms($post->ID, $taxonomy);
        $current = ($postterms ? array_pop($postterms) : false);
        $current = ($current ? $current->term_id : 0);

        ?>

        <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

            <!-- Display tabs-->

            <!-- Display taxonomy terms -->
            <div id="<?php echo $taxonomy; ?>-all" class="">
                <ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy ?> form-no-clear">

                    <?php
                    $i = 0;
                    foreach ($terms as $term) {

                        $id = $taxonomy . '-' . $term->term_id;
                        $checked = '';
                        if ($current === 0) {
                            if ($term->name === $default_format) {
                                $checked = 'checked';
                            }
                        } else if ($current == $term->term_id) {
                            $checked = 'checked';
                        }


                        echo "<li id='$id'><label class='selectit'>";
                        echo "<input type='radio' id='in-$id' name='{$name}'" . $checked . " value='$term->term_id' />$term->name<br />"; // checked($current,$term->term_id,false)
                        echo "</label></li>";
                        $i++;
                    } ?>
                </ul>
                <p class='note'>Choose default post format for this post.</p>
            </div>


        </div>

        <?php
    }

}

/* EOF */
