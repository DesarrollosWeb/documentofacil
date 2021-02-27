<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$acceptLang = ['es', 'pt'];
$lang = in_array($lang, $acceptLang) ? $lang : 'es';
require_once "lang_{$lang}.php";

const IS_DEVELOPMENT = false;
if (IS_DEVELOPMENT) {
// ** MySQL settings - You can get this info from your web host ** //
    /** The name of the database for WordPress */
    define('DB_NAME', 'DO278_WP');
    /** MySQL database username */
    define('DB_USER', 'DO278_WP');

    /** MySQL database password */
    define('DB_PASSWORD', 'G8;yV2;qX1(a');

    /** MySQL hostname */
    define('DB_HOST', 'documentofacil.com');

    /** Database Charset to use in creating database tables. */
    define('DB_CHARSET', 'utf8mb4');

    /** The Database Collate type. Don't change this if in doubt. */
    define('DB_COLLATE', '');

}

const STRIPE_API = "pk_test_51HV3x0AlxX0d4JWfw8wkiQCUhaHfSOlq99joORY4akPa2UV7lnAfKboDD6vMhevdMx1YyajljMxG3sdWmOUgWHVo00I6dmlj7c";
const STRIPE_API_SECRET = "sk_test_51HV3x0AlxX0d4JWf3VRedxULp5mk4CTK9wIRTPls75mWXXhWv2gslmFArxxXzSnut8fTInguT8rZ8JgoF55oxGLj00rhbbYOTc";

const ORDER_COMPLETED = "Completado";
const ONEDRIVE_CLIENT_ID = "e2b8f3e6-42cd-44ff-8c87-1a37d9b25c98";
if (strpos($_SERVER["HTTP_HOST"], "localhost")) {
    define("ONEDRIVE_REDIRECT_URI", "http://localhost:8080/redirect.php");
} else {
    define("ONEDRIVE_REDIRECT_URI", "https://www.documentofacil.com/redirect.php");
}
const ONEDRIVE_CLIENT_STATE = "onedrive.client.state";
const ONEDRIVE_CLIENT_SECRET = "-00V_Lqk1QT1Vv_wUf6ol6rbQeT_Upm8y8";


