<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {
	include( dirname( __FILE__ ) . '/local-config.php' );
	define( 'WP_LOCAL', true );
 } else if ( file_exists( dirname( __FILE__ ) . '/dev-config.php' ) ) {
	include( dirname( __FILE__ ) . '/dev-config.php' );
	define( 'WP_DEV', true );
} else {
	define( 'DB_NAME',     'pcqf_live_wp');
	define( 'DB_USER',     'pcomm_developer');
	define( 'DB_PASSWORD', 'cxzaq123');
}
define( 'DB_HOST',     'localhost'  );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'B@}G.x+G3m)3z4li&3Sh]{I3BVXlD|G3if-G?[ojsD-+j9>OTaW+rM}i?rdzxi]w');
define('SECURE_AUTH_KEY',  '{T7p-TAr~/[LN~n>Bwm_,YSz2QHc+Uha;PW0H/yA3=anH_z8kv &!EOiO5mUlSM_');
define('LOGGED_IN_KEY',    'W$-G;GYJ`]#r?U`d+T1Z/_5|%BIkxF1[;+f;`>RceG4 ^KK1.Qm %(D(J:vS{NBU');
define('NONCE_KEY',        'N(_+]q,bTDwx-!YH<ypwxy%]V&aX=R9%&d)zdMWg!%4-Lvy=c.rGu(-21or=Q/4&');
define('AUTH_SALT',        'HW,clnCEA6Mx<0M2V#t16focD!7d?XS?+:Q%KFNYL-P3-%>[+F7vpluI78(JfwmE');
define('SECURE_AUTH_SALT', 'P}Fk,R--|bMWwwmSPRru,z|?{Hs=eoO-&l+/nFyRkN_jMkO~>iavr&-<j=i}1vWk');
define('LOGGED_IN_SALT',   'ocv6o^QR)uyl2_ )SZLaeS:U5pgTr@-H([7OjC~@_d+Z_>q0BG8WRVPWrOEH|S4=');
define('NONCE_SALT',       '8eA9swRc0R@n0km4irE;p<4.^xJ*no `-J1s$`{/Q-Y[+k-*?ZS$-%{2#~t1HnEZ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
