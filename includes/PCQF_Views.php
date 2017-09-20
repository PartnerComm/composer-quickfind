<?php

/*
 * class PCQF_Views
 *
 * Templates and views for the PComm QuickFind Plugin.
*/

class PCQF_Views
{

  // Similar to __construct()
  public static function itit()
  {

  }

  public static function get_single_layout($layout, $data)
  {
    switch ($layout)
    {

      case "contact":
        $contact = self::render_contact($data);
        return $contact;
        break;

      case "tip":
        $tip = self::render_tip($data);
        return $tip;
        break;

      case "text";
        $text = self::render_text($data);
        return $text;
        break;

      case "info":
        $info = self::render_info($data);
        return $info;
        break;

      case "video";
        $video = self::render_video($data);
        return $video;
        break;

      case "footnote";
        $footnote = self::render_footnote($data);
        return $footnote;
        break;

      case "important":
        $important = self::render_important($data);
        return $important;
        break;

       case "sidebar":
        $sidebar = self::render_sidebar($data);
        return $sidebar;
        break;

      case "definition-text":
        $definition = self::render_definition($data);
        return $definition;
        break;

      default:
        $text = self::render_text($data);
        return $text;
        break;
    }
  }

  public static function get_group_layout($layout, $group)
  {
    switch ($layout)
    {
      case "highlighted-chart":
        $highlighted_chart = self::render_highlighted_chart($group);
        return $highlighted_chart;
        break;

      case "chart":
        $chart = self::render_chart($group);
        return $chart;
        break;

      case "comparison":
        $compare = self::render_comparison($group);
        return $compare;
        break;

      case "premium-table":
        $premium_table = self::render_premium_table($group);
        return $premium_table;
        break;

      case "footnote-group":
        $footnote_group = self::render_footnote_group($group);
        return $footnote_group;
        break;

      case "checkpoint-group":
        $checkpoint_group = self::render_checkpoint_group($group);
        return $checkpoint_group;
        break;

      default:
        $chart = self::render_chart($group);
        return $chart;
        break;
    }
  }

  public static function render_info($data)
  {
    $info = '<div class="info_container qf_view">';
    $info .= '<div class="info article"><i class="fa fa-info-circle"></i>';
    $info .= '<div class="article_content">';
    $info .= '<h3>' . $data['title'] . '</h3>';
    $info .= $data['formatted_content'];
    $info .= '</div></div></div>';

    return $info;
  }

  public static function render_info_group($group)
  {
    $info_group = '<div class="info_container qf_view">';
    $info_group .= '<div class="info article"><i class="fa fa-info-circle"></i>';
    $info_group .= '<div class="article_content">';
    foreach($group['group_post_order'] as $post)
    {
      $info_group .= '<h3>' . $post['title'] . '</h3>';
      $info_group .= $post['formatted_content'];
    }
    $info_group .= '</div></div></div>';

    return $info;
  }

  public static function render_footnote($data)
  {
    $footnote = '<div class="footnote_container qf_view">';
    $footnote .= '<article class="footnote">';
    $footnote .= '<p><strong>' . $data['title'] . ':</strong> ';
    $footnote .= $data['content'] . '</p>';
    $footnote .= '</article></div>';

    return $footnote;
  }

  public static function render_footnote_group($group)
  {
    $foot_group = '<div class="footnote_container qf_view">';
    $foot_group .= '<article class="footnote">';

    foreach($group['group_post_order'] as $post)
    {
      $foot_group .= '<h2>' . $post['title'] . '</h2>';
      $foot_group .= $post['formatted_content'];
    }

    $foot_group .= '</article></div>';

    return $foot_group;
  }

  public static function render_checkpoint_group($group)
  {
    $check_group = '<div class="checkpoint_container qf_view">';
    $check_group .= '<article class="checkpoint">';

    foreach($group['group_post_order'] as $post)
    {
      $check_group .= '<h2>' . $post['title'] . '</h2>';
      $check_group .= $post['formatted_content'];
    }

    $check_group .= '</article></div>';

    return $check_group;
  }

  public static function render_important($data)
  {
    $important = '<div class="important_container qf_view">';
    $important .= '<article class="important"><i class="fa fa-exclamation-triangle"></i>';
    $important .= '<div class="article_content">';
    $important .= '<h3>' . $data['title'] . '</h3>';
    $important .= $data['formatted_content'];
    $important .= '</div></article></div>';

    return $important;
  }

  public static function render_contact($data)
  {
    $resource = '<div id="contact-' . $data['post_name'] . '" class="contact_container qf_view">';
    $resource .= '<div class="contact article">';
    $resource .= '<h2>' . $data['title'] . '</h2>';
    $resource .= '<div class="article_content">';
    $administrator = $data['custom_content']['pc_administrator'][0];
    if (!empty($administrator)) {
      $resource .= '<p>' . $administrator . '</p>';
    }

    $contact = new PCommContacts();

    $resource .= $contact->output_phone_numbers($data['custom_content']['pc_phone_number']);
    $resource .= $contact->output_urls($data['custom_content']['pc_url'], $data['title']);

    $mailing_address = $data['custom_content']['pc_mailing_address'];
    if (!empty($mailing_address)) {
      $resource .= $contact->output_mailing_address($mailing_address);
    }

    $resource .= '</div></div></div>';

    return $resource;
  }

  public static function render_tip($data)
  {
    $tip = '<div class="plan_detail_tips_container post_type">';
    $tip .= '<div class="tip article"><i class="icon"></i>';
    $tip .= '<div class="article_content">';
    $tip .= '<h4>' . $data['title'] . '</h4>';
    $tip .= $data['formatted_content'];
    $tip .= '</div></div></div>';

    return $tip;
  }

  public static function render_text($data)
  {
    $text = '<div id="'. $data['post_name'] .'" class="text_container qf_view qf_view_'.
      $data['post_name'] . '">';
    $text .= '<article class="text">';
    $text .= '<h2>' . $data['title'] . '</h2>';
    $text .= $data['formatted_content'];
    $text .= '</article></div>';

    return $text;
  }

  public static function render_sidebar($data) {
    $sidebar = '<div class="sidebar_container qf_view">';
    $sidebar .= '<div class="sidebar_article article"><i class="icon"></i>';
    $sidebar .= '<div class="article_content">';
    $sidebar .= '<h4>' . $data['title'] . '</h4>';
    $sidebar .= $data['formatted_content'];
    $sidebar .= '</div></div></div>';

    return $sidebar;
  }

  public static function render_video($data)
  {
    $video = '<div class="video_container qf_view">';
    $video .= '<article class="video_view">';
    $video .= '<h2>' . $data['title'] . '</h2>';
    $video .= '<div class="video">' . do_shortcode( $data['content'] ) . '</div>';
    $video .= '</article></div>';

    return $video;
  }

  public static function render_definition($data)
  {
    $definition = '<div class="definition_container post_type">';
    $definition .= '<div class="definition article"><div class="article_content">';
    $definition .= '<h4>' . $data['title'] . ': </h4>';
    $definition .= $data['formatted_content'];
    $definition .= '</div></div></div>';

    return $definition;
  }

  public static function render_highlighted_chart($group)
  {
    // set controls for rows and cols
    $row_control = $group['rows_sorted'];
    $col_control = $group['cols_sorted'];

    // set content by row and column slug
    $row_content = $group['row_order'];
    $col_content = $group['col_order'];

    // determine number of columns and create string for class name
    $col_count = 'wizard_col_' . count($col_content);


    // create container for highlighed chart
    $highlighted_chart = '<div class="wizard_highlighted_chart post_type">';

    // loop through columns and add to container
    foreach($col_control as $col)
    {
      $item_count = 1;

      $highlighted_chart .= '<div class="clearfix wizard_highlighted_column ' . $col_count . '"><div class="wizard_col_pad">';
      $highlighted_chart .= '<h4>' . $col['name'] . '</h4>';

      // loop through each column row content
      $content = $col_content[$col['slug']];


      // populate content by row control slug
      foreach($row_control as $row)
      {
        if($item_count % 2)
        {
          $alt_class = "alt_row";
        }
        else
        {
          $alt_class = "reg_row";
        }

        $highlighted_chart .= '<div class="wizard_col_item ' . $content[$row['slug']]['group_layout'] . ' ' . $alt_class . '">';
        $highlighted_chart .= '<h5>' . $content[$row['slug']]['row_name'] . '</h5>';
        $highlighted_chart .= $content[$row['slug']]['formatted_content'];
        $highlighted_chart .= '</div>';

        $item_count++;
      }

      $highlighted_chart .= '</div></div>';

    }

    $highlighted_chart .= '</div><div style="clear:both;"></div>';

    return $highlighted_chart;
  }

  public static function render_chart($group)
  {
    //debug($group);
    // set control to either rows or cols
    $row_control = $group['rows_sorted'];
    $col_control = $group['cols_sorted'];

    // set content by row or column slug
    $row_content = $group['row_order'];
    $col_content = $group['col_order'];

    $col_count = count($col_control) + 1;

    if($col_count == 3) {
      $col_width = 'width="33%"';
    }
    elseif($col_count == 2) {
      $col_width = 'width="50%"';
    }
    else {
      $col_width = '';
    }

    // start table
    $chart = '<div class="chart_container qf_view"><div class="chart article respondtable"><table class="table_rendered">';

    // create thead and add blank first cell
    $chart .= '<thead><tr><th ' . $col_width . ' class="empty_head">&nbsp;</th>';

    $subhead = false;

    // add content for column heads using col_heads array as control
    foreach($col_control as $head)
    {
      $chart .= '<th class="' . $head['slug'] . '" '. $col_width . ' >' . $head['name'] . '</th>';
      if(!empty($head['subhead'])) {
        $subhead = true;
      }
    }

    if($subhead) {
      // create subhead row
      $chart .= '</tr><tr class="table_sub"><th class="empty_head">&nbsp;</th>';

      // add content for column subheads using col_heads array as control
      foreach($col_control as $subhead)
      {
        $chart .= '<th class="' . $subhead['slug'] . '">' . $subhead['subhead'] . '</th>';
      }
    }

    // close thead and start tbody
    $chart .= '</tr></thead><tbody>';

    // create each row using row_heads array as control
    $alt_count = 1;
    foreach($row_control as $row)
    {
      if($alt_count % 2) {
        $row_class = "alt_row";
      } else {
        $row_class = "row";
      }
      // start row
      $chart .= '<tr class="' . $row_class . '">';

      // add row headers using row_heads name
      $chart .= '<th><span>' . $row['name'] . '</span></th>';

      // grab all content by plan using the row slug as control
      $content = $row_content[$row['slug']];

      // use col heads for control again to populate cells with plan content
      foreach($col_control as $col)
      {
        //debug($col);
        // populate table content using plan slug as control
        $chart .= '<td data-title="' . $col['name'] . ' ' . $col['subhead'] . '" class="' . $col['slug'] . ' ' . $content[$col['slug']]['post_name'] . '">' . $content[$col['slug']]['formatted_content'] . '</td>';
      }

      // close row
      $chart .= '</tr>';

      $alt_count ++;
    }

    // close tbody and table
    $chart .= '</tbody></table></div></div>';

    return $chart;
  }

  public static function render_comparison($group)
  {
    //debug($group['group_name']);
    // set control to either rows or cols
    $row_control = $group['rows_sorted'];
    $col_control = $group['cols_sorted'];

    //debug($col_control);

    // Check for blank column head
    foreach($col_control as $k => $v) {
      if(empty($k)) {
        unset($col_control[$k]);
      }
    }

    //debug($col_control);

    // set content by row or column slug
    $row_content = $group['row_order'];
    //debug($row_content);
    $col_content = $group['col_order'];

    $col_count = count($col_control) + 1;
    //debug($col_count);

    if($col_count == 4) {
      $col_width = 'width="25%"';
    }
    elseif($col_count == 3) {
      $col_width = 'width="33%"';
    }
    elseif($col_count == 2) {
      $col_width = 'width="50%"';
    }
    else {
      $col_width = '';
    }

    // start table
    $chart = '<div class="comparison_container qf_view"><div class="comparison article respondtable ' . $group['group_name'] . '"><table class="comparison_table table_rendered">';

    // create thead and add blank first cell
    $chart .= '<thead><tr><th ' . $col_width . ' class="empty_head">&nbsp;</th>';

    $subhead = false;

    // add content for column heads using col_heads array as control
    $count_head = 1;

    foreach($col_control as $head)
    {
      $chart .= '<th '. $col_width . ' class="table_tab ' . $head['slug'] . ' column-' . $count_head . '">' . $head['name'] . '</th>';
      if(!empty($head['subhead'])) {
        $subhead = true;
      }

      $count_head ++;
    }

    if($subhead) {
      $count_sub = 1;
      // create subhead row
      $chart .= '</tr><tr class="table_sub"><th class="empty_head">&nbsp;</th>';

      // add content for column subheads using col_heads array as control
      foreach($col_control as $subhead)
      {
        if(empty($subhead['subhead'])) {
          $chart .= '<th class="column-' . $count_sub . '">&nbsp;</th>';
        } else {
          $chart .= '<th class="column-' . $count_sub . '"><strong>' . $subhead['subhead'] . '</strong></th>';
        }

        $count_sub ++;
      }
    }

    // close thead and start tbody
    $chart .= '</tr></thead><tbody>';

    // create each row using row_heads array as control
    $alt_count = 1;
    foreach($row_control as $row)
    {
      // grab all content by plan using the row slug as control
      $content = $row_content[$row['slug']];

      // get first key in row content array
      $key = key($content);

      // get group layout
      $group_layout = $content[$key]['group_layout'];

      switch ($group_layout) {
        case "comparison-text":
          //debug($content);
          $chart .= '<tr class="' . $group_layout . '"><th class="text_head">' . $content[$key]['title'] . '</th>';
          $chart .= '<td colspan="' . $col_count . '">' . $content[$key]['formatted_content'] . '</td></tr>';

          break;

        case "comparison-subhead":
          //debug($content);
          $chart .= '<tr class="' . $group_layout . '"><th colspan="' . $col_count . '" class="text_head comp_subhead">' . $content[$key]['title'] . '</th>';

          break;

        default:

          // default chart row for view
          if($alt_count % 2) {
            $row_class = "alt_row";
          } else {
            $row_class = "row";
          }

          // count for column classes
          $count_col = 1;

          // start row
          $chart .= '<tr class="' . $row_class . '">';

          // add row headers using row_heads name
          $chart .= '<th><span>' . $row['name'] . '</span></th>';

          // use col heads for control again to populate cells with plan content
          foreach($col_control as $col)
          {
            if($count_col % 2) {
              $col_class = "col";
            } else {
              $col_class = "alt_col";
            }
            // populate table content using plan slug as control
            $chart .= '<td data-title="' . $col['name'] . ' ' . $col['subhead'] . '" class="' . $content[$col['slug']]['post_name'] . ' column-' . $count_col . ' ' . $col_class . '">' . $content[$col['slug']]['formatted_content'] . '</td>';

            $count_col++;
          }

          // close row
          $chart .= '</tr>';

          $alt_count ++;

          break;
      }


    }

    // close tbody and table
    $chart .= '</tbody></table></div></div>';

    return $chart;
  }

  public static function render_premium_table($group)
  {

    // set control to either rows or cols
    $row_control = $group['rows_sorted'];
    $col_control = $group['cols_sorted'];

    // set content by row or column slug
    $row_content = $group['row_order'];
    $col_content = $group['col_order'];

    // start table container
    $prem_table = '<div class="costs plan_detail_costs_container post_type">';

    // add heading with salary band
    $prem_table .= '<h4>Premiums <span>(Salary Band: ' . $group['premium_group'] . ')</span></h4>';

    // begin table
    $prem_table .= '<table class="table_rendered">';

    // create thead and add blank first cell
    $prem_table .= '<thead><tr><th>&nbsp;</th>';

    // add content for column heads using col_heads array as control
    foreach($col_control as $head)
    {
      $prem_table .= '<th colspan="2">' . $head['name'] . '</th>';
    }

    // add row for employee and employer contributions
    // NEEDS BETTER SOLUTION HERE
    $prem_table .= '</tr><tr><th>&nbsp;</th>';
    $prem_table .= '<th>Your share</th><th>Rockwell&rsquo;s share</th>';
    $prem_table .= '<th>Your share</th><th>Rockwell&rsquo;s share</th>';

    // close thead and start tbody
    $prem_table .= '</tr></thead><tbody>';

    // create each row using row_heads array as control
    foreach($row_control as $row)
    {
      // start row
      $prem_table .= '<tr>';

      // add row headers using row_heads name
      $prem_table .= '<th>' . $row['name'] . '</th>';

      // grab all content by plan using the row slug as control
      $content = $row_content[$row['slug']];

      // use col heads for control again to populate cells with plan content
      foreach($col_control as $col)
      {
        // populate table content using custom content fields employee and employer costs
        $prem_table .= '<td>$' . $content[$col['slug']]['custom_content']['pc_employee_annual'] . '</td>';
        $prem_table .= '<td>$' . $content[$col['slug']]['custom_content']['pc_employer_annual'] . '</td>';
      }

      // close row
      $prem_table .= '</tr>';
    }

    // close tbody and table
    $prem_table .= '</tbody></table></div>';

    return $prem_table;
  }


}

/* EOF */
?>