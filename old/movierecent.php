<?php
	require_once('inc/functions.php');
	get_header();
?>

<div class="movieall-charchooser">
<hr/>
	<div class="clear"></div>

</div>

<?php

		$offset = $_GET['offset'];

        if (empty($offset) || !is_numeric($offset))
        {
            $offset = 0;
        }
            $sql = "
                    SELECT idFile, c00, strPath, playCount, c08
                    FROM movieview 
                    ORDER BY `idMovie` DESC LIMIT 20 OFFSET :offset
                ";

            // The database connection
            $DBH = db_handle();
            $STH = $DBH->prepare($sql);
            $STH->execute( array('offset' => $offset) );

		// Fetches the array of each movie and saves in another array.
		// An array named $result containing an array for each movie.
		$result = $STH->fetchAll(PDO::FETCH_ASSOC);

        echo '<center> Recently Added Movies </center><br/>';
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
?>

<?php
    $footer_data = "<center>";
    if ($offset > 0)
    {
        $val = ($offset - 20) > 0 ? ($offset - 20) : 0;
        $footer_data .= "<a href='movierecent.php?offset=".$val."'>Previous 20</a> | ";
    }
    if (count($result) > 0)
    {
        $footer_data .= "<a href='movierecent.php?offset=".($offset+20)."'>Next 20</a>";
    }
    $footer_data .="</center>";
?>
<?php

	get_footer($footer_data);
?>

