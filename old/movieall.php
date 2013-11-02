<?php
	require_once('inc/functions.php');
	get_header();
?>

<div class="movieall-charchooser">

	<?php
		// This prints out links to all the movies beginning with the first letter of the alphabet
		$alphabet = range('a', 'z');

        echo '<a href="movieall.php?">(Show All)</a>';
        echo '<a href="movieall.php?char=0">#</a>';
		foreach($alphabet as $letter) {
			echo '<a href="movieall.php?char=' . $letter . '">' . strtoupper($letter) . '</a>';
		}
	?>
<hr/>
	<div class="clear"></div>

</div>

 
<?php

	// Checks if there is a char set in the url bar, otherwise -> else
	if (isset($_GET['char'])) {
		
		$sel_char = $_GET['char'];

        if ($sel_char == '0')
        {
            $sql = "
                    SELECT idFile, c00, strPath, playCount, c08
                    FROM movieview
                    WHERE ";
                    foreach($alphabet as $letter)
                    {
                        $and = 'and';
                        if ($letter == 'z')
                            $and='';
                        $sql.= " LEFT(c00,1) !='".$letter."' $and
                            ";
                    }
                    $sql.="ORDER BY c00 ASC";
                 // The database connection
                $DBH = db_handle();
                $STH = $DBH->prepare($sql);
                $STH->execute( );

        }
        else
        {
            $sql = "
                    SELECT idFile, c00, strPath, playCount, c08
                    FROM movieview 
                    WHERE LEFT(c00,1) = :char 
                    ORDER BY c00 ASC
                ";

            // The database connection
            $DBH = db_handle();
            $STH = $DBH->prepare($sql);
            $STH->execute( array('char' => $sel_char) );
        }
    }
    else
    {
         $sql = "
                    SELECT idFile, c00, strPath, playCount, c08
                    FROM movieview 
                    ORDER BY c00 ASC
                ";

            // The database connection
            $DBH = db_handle();
            $STH = $DBH->prepare($sql);
            $STH->execute( );

    }
		// Fetches the array of each movie and saves in another array.
		// An array named $result containing an array for each movie.
		$result = $STH->fetchAll(PDO::FETCH_ASSOC);

        echo '<center> Found '.count($result).' Movies</center><br/>';
		// Loops through the array and prints out information for every movie
		for($i = 0, $size = sizeof($result); $i < $size; ++$i) {
			
			$movieID = $result[$i]["idFile"];
			$movieHash = get_hash($result[$i]["strPath"]);
			$movieName = $result[$i]['c00'];
            $moviePlayed = $result[$i]['playCount'];
            $movieThumbs = $result[$i]['c08'];
            if (!empty($movieThumbs))
            {
                $thumbXML = new SimpleXMLElement('<thumbs>'.$movieThumbs.'</thumbs>');
                $movieThumbs = $thumbXML->thumb[0];
            }
            else
            {
                $movieThumbs = "";
            }
?>

<div class="coverframe">
	
	<a href="moviedetails.php?id=<?php echo $movieID; ?>">
		<div class="coverframe-picture" style="background:url(<?php echo $movieThumbs; ?>) no-repeat center center; background-size:122px auto;">
		</div>
	</a>
	
	<div class="coverframe-text">
		<a href="moviedetails.php?id=<?php echo $movieID; ?>"><?php echo $movieName; ?></a>
	</div>

</div>

<?php
    }
	get_footer();
?>
