<?php

require_once("helper.php");

/*	
 *	CONFIG FILE
 *
 *	This is where you store all your configuration files.
 *	Connections to the database for example.
 *
 *	Edit this file with your settings.
 *	Keep this file secure becuase passwords are kept in plain text!
 *	Preferably you should edit your .htaccess or keep this file outside the root folder.
 *
 *	RENAME THIS FILE TO config.php WHEN DONE EDITING, SAVE IN THE SAME LOCATION ( /inc ).
 */

/* ==|== Database ===============================================================================
	Edit these four lines with the credentials to your database.
   ============================================================================================== */

	// The server IP to your database:
	addDefine('DB_HOST', 'localhost');
	
	//	The name of the database:
	addDefine('DB_NAME_SITE', 'MyVideos75');
	
	//	The name of the database:
	addDefine('DB_NAME_VIDEO', 'MyVideos75');
	
	//	The name of the database:
	addDefine('DB_NAME_MUSIC', 'MyMusic32');
	
	// The username of the user who should access the database:
	addDefine('DB_USER', 'xbmc');
	
	// The password for the user:
	addDefine('DB_PASS', '');


/* ==|== Misc ===================================================================================
	Misc stuff
   ============================================================================================== */
	
	//The Movie Database API
	addDefine('TMDB_API', '');

	//The TV Database API
	addDefine('TTVDB_API', '');

    //Google Analytics ID
    addDefine('GOOGLE_ANALYTICS', '');

    // Domain for Google Analytics
    addDefine('GOOGLE_ANALYTICS_DOMAIN', '');

    addDefine('FTP_PORT', '');

    // base path of your mvc-php application
    addDefine('MVC_APP_PATH','/media/XBMC/');
    // base url of the app
    addDefine('MVC_APP_URL','/media/XBMC/public/');
    // path of our controllers
    addDefine('MVC_CONTROLLER_PATH', MVC_APP_PATH.'includes/controllers/');
    // base path of Smarty
    addDefine('SMARTY_PATH', '/usr/share/php/Smarty/'); // this is the default on Fedora
    // if we want to  enable smarty
    addDefine('USE_SMARTY', false);

    // debug levels
    addDefine('DEBUG_MAIN', 1);
    addDefine('DEBUG_VERBOSE', 2);

    addDefine('DEBUG_LOG', 'log');
    addDefine('DEBUG_HTML', 'html');

    //control debug
    addDefine('DEBUG_ENABLED',false);
    addDefine('DEBUG_LEVEL', DEBUG_MAIN);
    addDefine('DEBUG_FUNCTION', DEBUG_LOG); 


    if (USE_SMARTY)
    {
        require_once(SMARTY_PATH."/Smarty.class.php");
    }

?>
