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
define( 'DB_NAME', 'wp-test' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Ky$vtRl%.5;^w*hX MIei/O,Py~J9^^UAcCyf~PS6WUWX2t$b++j@PR%1L3fW]_(' );
define( 'SECURE_AUTH_KEY',  'QU*sR(e{%1;;|k{<51U|lkrg+FE&+MMXZ29<chNDVt~-~+1AgWTNy1 ^,`]DO,Iw' );
define( 'LOGGED_IN_KEY',    '*m)3ssp/U1mr`T](Ci@;R#ysiO,n!Q5^qtA[c(|6F^KiXOACUGnxit;4;]XdT%?x' );
define( 'NONCE_KEY',        '5e:e$Y|=pAQg2JD=:]]/0`+1+fMet:yXJ0(A)zZ;>gqXr*t?vrC~_{zO{K6[Dnr,' );
define( 'AUTH_SALT',        'l^nNRU5kI6mHJu&_3yELV#dd.K_ 5#yS%Crr^S3f&$~xgpA+V]@rkyQ/h[Y(<igJ' );
define( 'SECURE_AUTH_SALT', ')v;f!e)Lw$t1Q5_?19j(#AKX}tbqFf}?L{Phz+mW1{(nOh|Tu74Joq9Kz0N>gtF.' );
define( 'LOGGED_IN_SALT',   ']oK%uSk~g>i<^3GjqTYN>9 CE>lk>G4c*- HN6*[Cj%8%=|Gfp(%WO6Ki:-$N0SE' );
define( 'NONCE_SALT',       'GUS.!qbPtRyihUVR@%@*qNVs~7<3 cJ2d#MO`HnTlHRhz6jrOx-.,alD_FTI+iHJ' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
