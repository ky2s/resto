<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'etaw7379_wp_resto' );

/** Database username */
define( 'DB_USER', 'etaw7379_wp_user_resto' );

/** Database password */
define( 'DB_PASSWORD', 'ep#w~F*7?3oo4k5K' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Ob&Cb!9I hgz{>^HR(>t8S8P:%s-Z4.Ovw]{e)D2DF*:.r#W<Q-ii=L~x,~5rr79' );
define( 'SECURE_AUTH_KEY',  '5:|})4 K,f $h=SkiD<44#`KxyT }3nV5.Hjl3M%r;wqlchJ!U*i=p=^V]F7B=#J' );
define( 'LOGGED_IN_KEY',    '<ZXl7~Aw1lP=I&WX3m_?Og;R8q5K !{`r@%l,g0_]|W&qFPCfw~Fb@VMh20a)-F>' );
define( 'NONCE_KEY',        '1VgRBIG&wI30iCa8/]#p%@~RamCyr6.)dt/R4boO.)Zp}UfO[C{6K55i@&,ar(S.' );
define( 'AUTH_SALT',        ':)SHFVTA3Jv9TtoU>:z^,,6}R:4;Xc3[m>)!djrx^#[&.Hl$V{~oA,6dhtCN^Qv%' );
define( 'SECURE_AUTH_SALT', 'AwY]x@RH/+D{0Mv@r2i!z!jg<cryG3(8zqPL0w9N_o0k:6T=uM*B4GEp1|2H`BZr' );
define( 'LOGGED_IN_SALT',   ':Q)Qjf)S~2z!b:zuS%Xj}@<SZ)=sV{[(VSDb-_@$8#lT|s>uFi)VbNtx[t,3vK)&' );
define( 'NONCE_SALT',       'T6H:.60-v{Q.T}(Gk/hAKQ08p)kb,#Oih-{@n3b>B@C))|kgSB)Ty/<&Q<s!i69P' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'resto_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
