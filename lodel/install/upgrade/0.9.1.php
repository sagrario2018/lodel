<?php
	define('backoffice-lodeladmin', true);

	require_once 'lodelconfig.php';
	require_once 'lodel/scripts/context.php';
	C::setCfg($cfg);

	require_once 'lodel/scripts/connect.php';
	require_once 'lodel/scripts/auth.php';
	
	/* Vérification que nous sommes en php-cli, sinon nous vérifions les droits*/
	if (!array_key_exists('SHELL', $_ENV))
		authenticate(LEVEL_ADMINLODEL);

	global $db;
	$sites = $db->GetRow(lq("
            SELECT name, status 
                FROM #_MTP_sites 
                WHERE status > 0")) 
            		or trigger_error("SQL ERROR :<br />".$GLOBALS['db']->ErrorMsg(), E_USER_ERROR);

	
	foreach($sites as $site){
		$db->SelectDB(DATABASE . "_{$site}");
		$db->execute('ALTER ' .
				'TABLE `tablefields` ' .
				'ADD COLUMN `editionhooks` TEXT NOT NULL ' .
				'AFTER `editionparams`') 
		or trigger_error("SQL ERROR: " $db->ErrorMsg()), E_USER_ERROR);
	}

?>