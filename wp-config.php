<?php
# Database Configuration
define('WP_HOME','http://repakelt.local');
define('WP_SITEURL','http://repakelt.local');
define( 'DB_NAME', 'repakelt' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'ddipass' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_HOST_SLAVE', '127.0.0.1' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');

define( 'DISALLOW_FILE_EDIT', FALSE );
$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         '_m#-/D,D.&Q,XC||<yBcG^x&OYj9`.t;?pc2`4uKlFRh&kn>GA$pjSO^<w^=#gp ');
define('SECURE_AUTH_KEY',  '9}C]A,iFO|[4LFvP6ivzWE!&fwh<zK)<5 ^gZob>X=>i3U/XMg7o^?6vl?Pya$l`');
define('LOGGED_IN_KEY',    'JqL:V9|^V7jU1n,]TI{fS*+0K=o:C+IUvQD1W^vLbdDO:e<PJgZj<iZkSO`(#[u0');
define('NONCE_KEY',        ']r-tus2ve&QHF,|4Dt%FE34nABT*S+E+yF8J-T%W&%i%bdNypm$(U6S<Rz1K[@-}');
define('AUTH_SALT',        '||vOzwI-N-I1geyE&HwWS6c]4rmpLtK1*v]e%6_8(,e[60-+iT[WX;Tm&=Og]&O<');
define('SECURE_AUTH_SALT', 'R{ae_QO*G[Mv*y$|->oNwG|EaT{+X 410o+8+`uH)luWvBti-fqC~gSRR<,V1Yxo');
define('LOGGED_IN_SALT',   '|<]kIGBcE|~.faK1XKPWQwtrRM5Q#7[L)f^0}j``>TLVL(>(/21-i_W|M68%e8 K');
define('NONCE_SALT',       'ydNB+_,,.|nKanJh/1<A-@6;55@fABd*[pI&YdUOYFUifDqN+-lG+ma)bTv6|Ti%');


# Localized Language Stuff

define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'repakelt2017' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'PWP_ROOT_DIR', '/nas/wp' );

define( 'WPE_APIKEY', '900a134fac1950ced615e36f7e2c1cd5f31d07cf' );

define( 'WPE_CLUSTER_ID', '101400' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );

/*SSLSTART*/ if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/
/*SSLSTART*/ if ( isset($_SERVER['X-Forwarded-Proto']) && $_SERVER['X-Forwarded-Proto'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/


define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', FALSE );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'repakelt.ie', 1 => 'www.repakelt.ie', 2 => 'repakelt2017.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-101400', );

$wpe_special_ips=array ( 0 => '35.189.110.252', );

$wpe_ec_servers=array ( );

$wpe_largefs=array ( );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );
define('WPLANG','');

# WP Engine ID


# WP Engine Settings

define( 'WPE_GOVERNOR', false );

# Debug


# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
require_once(ABSPATH . 'wp-settings.php');








