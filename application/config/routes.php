<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/user/login'] = 'api/UsersApi/login';
$route['api/user/create_update_profile'] = 'api/UsersApi/createUpdateAccount';
$route['api/user/update_password'] = 'api/UsersApi/updatePassword';
$route['api/user/forget_password'] = 'api/UsersApi/forgetPassword';
$route['api/user/delete'] = 'api/UsersApi/deleteProfile';
$route['api/user/read_profile'] = 'api/UsersApi/readProfile';
$route['api/user/activation_profile'] = 'api/UsersApi/activationProfile';

$route['api/category/read_category'] = 'api/CategoryApi/readCategory';
$route['api/category/delete'] = 'api/CategoryApi/deleteCategory';
$route['api/category/create_update_category'] = 'api/CategoryApi/createUpdateCategory';

$route['api/office/read_office'] = 'api/OfficeApi/readOffice';
$route['api/office/delete'] = 'api/OfficeApi/deleteOffice';
$route['api/office/create_update_office'] = 'api/OfficeApi/createUpdateOffice';

$route['api/seat/read_seat'] = 'api/SeatApi/readSeat';
$route['api/seat/delete'] = 'api/SeatApi/deleteSeat';
$route['api/seat/create_update_seat'] = 'api/SeatApi/createUpdateSeat';