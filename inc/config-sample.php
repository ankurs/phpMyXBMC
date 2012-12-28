<?php

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
	define('DB_HOST', 'localhost');
	
	//	The name of the database:
	define('DB_NAME_SITE', 'xbmc_site');
	
	//	The name of the database:
	define('DB_NAME_VIDEO', 'xbmc_video');
	
	//	The name of the database:
	define('DB_NAME_MUSIC', 'xbmc_music');
	
	// The username of the user who should access the database:
	define('DB_USER', 'xbmc');
	
	// The password for the user:
	define('DB_PASS', '');


/* ==|== Misc ===================================================================================
	Misc stuff
   ============================================================================================== */
	
	//The Movie Database API
	define('TMDB_API', '');

	//The TV Database API
	define('TTVDB_API', '');

    //Google Analytics ID
    define('GOOGLE_ANALYTICS', '');

    // Domain for Google Analytics
    define('GOOGLE_ANALYTICS_DOMAIN', 'satyanas.in');

?>
