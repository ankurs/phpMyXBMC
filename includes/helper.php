<?php

/* ==|== Includes ===============================================================================
	All files that should be included on the homepage, for example Header and Footer.
   ============================================================================================== */

	//	Config file
	require('config.php');
	
	//	Header
	function get_header() {
		include('template_helper/header.php');
	}

	//	Sidebar
	function get_sidebar() {
		include('template_helper/sidebar.php');
	}

	//	Footer
	function get_footer($footer_data = null) {
		include('template_helper/footer.php');
	}

    function get_updates()
    {
        include('template_helper/update.php');
        return $updates;
    }

/* ==|== Database ===============================================================================
	All database related.
   ============================================================================================== */
	
	// The function for the database connection and quering/execute. 
	// Defaults to video database if nothing else is specified.
	function db_handle($database = DB_NAME_VIDEO) {
		try {
			$DBH = new PDO("mysql:host=".DB_HOST.";dbname=$database", DB_USER, DB_PASS);
			$DBH->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, 1);
			$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
			return $DBH;
		}
		catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
	}



function should_debug($level)
{
    if (defined("DEBUG_ENABLED") && DEBUG_ENABLED)
    {
        if (defined("DEBUG_LEVEL") && DEBUG_LEVEL >= $level)
        {
            return true;
        }
    }
    return false;
}

function debug($string, $level=1)
{
    if (should_debug($level))
    {
        if (DEBUG_FUNCTION == "log")
        {
            error_log($string);
        } 
        else if (DEBUG_FUNCTION == "html")
        {
            echo "<br/>".$string."<br/>";
        }
        // TODO saperate log file
    }
}

function addDefine($key, $value)
{
    if (!defined($key))
    {
        define($key, $value);
    }
}

function getURL($controller=null, $action=null, $params=null)
{
    if (!is_array($params) && !is_null($params))
    {
        $params = array($params);
    }
    $url = MVC_APP_URL;
    if (!is_null($controller) && is_string($controller))
    {
        $url .= $controller.'/';
        if (!is_null($action) && is_string($action))
        {
            $url .= $action.'/';
            if (is_array($params))
            {
                $url .= implode('/', $params);
            }
        }
    }
    return $url;
}

?>
