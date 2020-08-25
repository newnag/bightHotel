<?php
error_reporting(1);
ini_set('display_errors', 1);

    // Globla Config
date_default_timezone_set("Asia/Bangkok");

/**
* Database config variables
*/
define('WEB_VERSION', '2.1.2');


define("DB_HOST", "localhost"); 
define("DB_DATABASE", "tero89_hotel_new");
define("DB_USER", "tero89_hotel_new"); 
define("DB_PASSWORD", "^KMmqx+n0@u5"); 
define("DB_CHARSET", "SET NAMES UTF8");

define('DEFAULT_LANGUAGE','TH');//ตั้งค่าภาษาเริ่มต้น

/**
 * Path & Url config variables
 */

$subUrl = '';
define("ROOTPATH", "/");
define("DOC_ROOT", $_SERVER['DOCUMENT_ROOT']);
define("SITE_URL", (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/" . $subUrl . "");
define("BASE_URL", (isset($_SERVER['HTTPS']) ? "https" : "https") . "://$_SERVER[HTTP_HOST]/" . $subUrl . "");
define("ROOT_URL", (isset($_SERVER['HTTPS']) ? "https" : "https") . "://$_SERVER[HTTP_HOST]/" . $subUrl . "");
define("AJAX_REQUEST_URL", (isset($_SERVER['HTTPS']) ? "https" : "https") . "://$_SERVER[HTTP_HOST]/" . $subUrl . "");
define("WEB_SITE_URL","https://ktdev.site");


// กำหนด Path ต่างๆ และ กำหนด Thumbnail size
define('PATH_UPLOAD', $_SERVER['DOCUMENT_ROOT'] . '/upload/');
define("SITE_THUMGEN", SITE_URL.'classes/thumb-generator/thumb.php');
define("SITE_THUMBGEN", SITE_URL.'classes/thumb-generator/thumb.php');
define("SIZE_IMG_MOBILE", '&size=x250');
define("SIZE_IMG", '&size=x300');
