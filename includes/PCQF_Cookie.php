<?php

/*
 * class PCQF_Cookie
 * 
 * Gets and sets cookie with user's location for use in QuickFind content display.
*/

class PCQF_Cookie
{

  // Initializes plugin, similar to __construct()
  public static function init()
  {

  }

  /*******************************************************************************
   ** SET COOKIE **
   *******************************************************************************/
  /*public static function bloomin_set_cookie() {
    $domain = str_replace('http://', '', get_home_url());
    $expire = time()+(60*60*24); // expire 1 day from now
    $expireLong = time()+3600*24*30; // expire 30 days from now
    if(!isset($_COOKIE['type']))
    {
      if(isset($_GET['type']))
      {
        // lets make our cookie!
        setcookie('type', $_GET['type'], $expireLong, '/');
      }
      else
      {
        setcookie('type', 'salaried', $expire, '/');

        //  wp_redirect( get_home_url(), $status = 302 );
        //  exit;

      }

    }
    elseif(isset($_COOKIE['type']))
    {
      $expire2 = time()+(60*60*24);

      if(isset($_GET['type']))
      {
        if($_GET['type'] == 'hourly' || $_GET['type'] == 'salaried') {
          // lets make our cookie!
          setcookie('type', '', time() - 3600, '/');
          setcookie('type', $_GET['type'], $expireLong, '/');
        }
      }

    }

  };*/

  public static function get_cookie() {
    if (isset( $_COOKIE['rocheqf_user_preferences'] ) ) {
      return $_COOKIE['rocheqf_user_preferences'];
    }
    else {
      return;
    }
  }

}

/* EOF */
?>