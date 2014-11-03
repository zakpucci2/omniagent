<?php

$config['Email'] = array('EmailSupport' => 'info@omnihustle.net');

$config['SITENAME'] = array(
	'Name' => 'OmniHustle',
	'Copyright' => "&copy; Powered by: OmniHustle"
);

$config['IMAGES_SIZES_DIR'] = array(
	'ProfilePhoto' => 'profile_photo',
	'ProfilePhoto40x40' => 'profile_photo40x40',
	'ProfilePhoto150x150' => 'profile_photo150x150',
);

$config['LIST_NUM_RECORDS'] = array(
	'User' => 10,
	'Admin' => 10,
	'Superadmin' => 10,
	'Gallery' => 6
);

$config['TicketStatus'] = array(
	1 => 'Open',
	2 => 'Assigned',
	3 => 'Processing',
	4 => 'Resolved',
	5 => 'Closed'
);

$config['PriceType'] = array(
	1 => 'Daily',
	2 => 'Monthly',
	3 => 'Yearly'
);

$config['SETTINGS'] = array(
    'CURRENCY' => 'USD',
    'CURRENCY_ENTITY' => "&#36;",
    'ORDER_STATUS' => array(
        1 => 'Open',
        2 => 'Recieved',
        3 => 'Cancelled',
        4 => 'Delievred',
    )
);

$config['ImagesColumns'] = array(
	'profile_photo',
	'image',
	'image_name',
	'cover_photo',
	'info5'
);

$config['SITE_EMAIL'] = array('Email' => 'omnihustle.net');
$config['PROJECT_BASE_PATH'] = array('URL' => dirname(dirname(dirname($_SERVER['PHP_SELF']))));
$config['FULL_BASE_URL'] = array('URL' => $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . dirname(dirname(dirname($_SERVER['PHP_SELF']))));

if (!defined('ELFINDER_DIR')) {
  // According to your server path and setup        
  define ('ELFINDER_DIR', ROOT . '/app/webroot/files/');
}
if (!defined('ELFINDER_URL')) {
  // According to your domain and setup     
  define ('ELFINDER_URL', $config['FULL_BASE_URL']['URL']. '/files/');
}

$config['LOGIN_URL'] = array('URL' => $config['FULL_BASE_URL']['URL'] . '/users/login');
$config['SIGNUP_URL'] = array('URL' => $config['FULL_BASE_URL']['URL'] . '/users/signup');

$config['DATE_FORMAT'] = array('Date' => '%d/%m/%Y');
$config['DATETIME_FORMAT'] = array('DateTime' => '%d/%m/%Y %H:%M %p');
$config['DB_DATE_TIME_FORMAT'] = array('DateTime' => '%Y-%m-%d %H:%i:%s');
$config['DB_SAVE_DATETIME_FORMAT'] = array('DateTime' => 'Y-m-d H:i:s');

$config['IMAGE_SIZES'] = array(
	'ProfilePic' => array('width' => 125, 'height' => 125),
	'GalleryImage' => array('width' => 320, 'height' => 200),
	'SenderAvatar' => array('width' => 40, 'height' => 40)
);

$config['UserType'] = array(
	'superadmin' => 1,
	'admin' => 2,
	'user' => 3,
);

$config['PrivelagesType'] = array(
	'normal' => 0,
	'regular' => 1,
	'trusted' => 2,
);

$config['CardTypes'] = array(
	'Visa' => 'Visa',
	'Mastercard' => 'Mastercard',
	'American Express' => 'American Express',
	'Discover' => 'Discover'
);

$config['TimeZones'] = array(
	'GMT -12:00' => '(GMT -12:00) Eniwetok, Kwajalein',
	'GMT -11:00' => '(GMT -11:00) Midway Island, Samoa',
	'GMT -10:00' => '(GMT -10:00) Hawaii',
	'GMT -9:00' => '(GMT -9:00) Alaska',
	'GMT -8:00' => '(GMT -8:00) Pacific Time (US & Canada)',
	'GMT -7:00' => '(GMT -7:00) Mountain Time (US & Canada)',
	'GMT -6:00' => '(GMT -6:00) Central Time (US & Canada), Mexico City',
	'GMT -5:00' => '(GMT -5:00) Eastern Time (US & Canada), Bogota, Lima',
	'GMT -4:00' => '(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz',
	'GMT -3:30' => '(GMT -3:30) Newfoundland',
	'GMT -3:00' => '(GMT -3:00) Brazil, Buenos Aires, Georgetown',
	'GMT -2:00' => '(GMT -2:00) Mid-Atlantic',
	'GMT -1:00' => '(GMT -1:00 hour) Azores, Cape Verde Islands',
	'GMT +0:00' => '(GMT) Western Europe Time, London, Lisbon, Casablanca',
	'GMT +1:00' => '(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris',
	'GMT +2:00' => '(GMT +2:00) Kaliningrad, South Africa',
	'GMT +3:00' => '(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg',
	'GMT +3:30' => '(GMT +3:30) Tehran',
	'GMT +4:00' => '(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi',
	'GMT +4:30' => '(GMT +4:30) Kabul',
	'GMT +5:00' => '(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent',
	'GMT +5:30' => '(GMT +5:30) Bombay, Calcutta, Madras, New Delhi',
	'GMT +5:45' => '(GMT +5:45) Kathmandu',
	'GMT +6:00' => '(GMT +6:00) Almaty, Dhaka, Colombo',
	'GMT +7:00' => '(GMT +7:00) Bangkok, Hanoi, Jakarta',
	'GMT +8:00' => '(GMT +8:00) Beijing, Perth, Singapore, Hong Kong',
	'GMT +9:00' => '(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
	'GMT +9:30' => '(GMT +9:30) Adelaide, Darwin',
	'GMT +10:00' => '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
	'GMT +11:00' => '(GMT +11:00) Magadan, Solomon Islands, New Caledonia',
	'GMT +12:00' => '(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka'
);