<?
	define( 'LIB_PATH', realpath(dirname(__FILE__).'/') );

    // Cookie secret key
    define('COOKIE_SECRET_KEY','');

    // Administrator
    define('ADMIN_ID','');
    define('ADMIN_PASSWD','');

    // E-mail setting
    define('EMAIL_FROM_ADDR','');
    define('EMAIL_FROM_NAME','');

    // Database setting
    define('DB_HOST','');
    define('DB_NAME','');
    define('DB_USER','');
    define('DB_PASSWD','');

    // Facebook app setting 
    define('FACEBOOK_APPID', '');
    define('FACEBOOK_SECRET', '');
    define('FACEBOOK_CALLBACK', 'http://'.$_SERVER['SERVER_NAME'].'/member/oauth/facebook/callback.php');

    // Twitter app setting
    define('CONSUMER_KEY', '');
    define('CONSUMER_SECRET', '');
    define('TWITTER_CALLBACK', 'http://'.$_SERVER['SERVER_NAME'].'/member/oauth/twitter/callback.php');

    // SlideShare API
    define('SLIDESHARE_KEY', '');
    define('SLIDESHARE_SECRET', '');
