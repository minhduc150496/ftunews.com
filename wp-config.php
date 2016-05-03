<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ftunews.com');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '}4k{dvN{0M8YXD(A|!]7)v1yu8<~:0^(s0Y3tUrl:>$lRr@SS.N#&Hq%%V*)7HUZ');
define('SECURE_AUTH_KEY',  'S6skL0A:rG>$_/2_bAda]8!Yk0V|<L$I`^,N!U0ygvu0nU?!V~5b;8^K6`nu#l,n');
define('LOGGED_IN_KEY',    'Im/QF66xw$Cz6gcVb:PRMWs%Xo2G^tso[/^x};@*Wj7Z;Y8Th6% VG)MO1Ox81-#');
define('NONCE_KEY',        'u`8k15gYrDFMDF8?Z*Lg{/J%TXkv>!^SGPh-hZ)]:5w0BoJ^u6}[A{s^@+O;>$f{');
define('AUTH_SALT',        'PW;&3E^3I,,d)VhRg8 !S<jtvk-b[SPcc2^b0XX:9oWy tH|nE&Um}u).>]b><Ce');
define('SECURE_AUTH_SALT', '<m$gqgWSd|k)XnEEE_b_2(S<(T1_z02u%r}#|Zg,~GS*luSPRv}SPFQl1@*Zb/]w');
define('LOGGED_IN_SALT',   '=3Q;mG%7$!Z/_V`M.ksM_NY{83qvq96|!Upu|jY3~|9M*xk#)Eq]X~{ho!*4*iUm');
define('NONCE_SALT',       'rA[p=E9R4hE}Qp+eH9a!%Nx~kYf5VRRr|X1dSqI+GZ2$2xj,.aJsKalAV?G+1gB{');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
