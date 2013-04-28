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
                    SELECT * FROM `episodeview`
                    ORDER BY `dateAdded` DESC LIMIT 20 OFFSET :offset
                ";

            // The database connection
            $DBH = db_handle();
            $STH = $DBH->prepare($sql);
            $STH->execute( array('offset' => $offset) );

		// Fetches the array of each movie and saves in another array.
		// An array named $result containing an array for each movie.
		$result = $STH->fetchAll(PDO::FETCH_ASSOC);

        echo '<center> Recently Added TV Episodes </center><br/>';
		// Loops through the array and prints out information for every episode
		for($i = 0, $size = sizeof($result); $i < $size; ++$i) {
			
			$showID = $result[$i]["idEpisode"];
            $showName = $result[$i]["strTitle"];
			$EpisodeName = $result[$i]['c00'];
            $movieThumbs = $result[$i]['c06'];
            $dateAdded = $result[$i]['dateAdded'];
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
	
	<a href="episodedetails.php?id=<?php echo $showID; ?>">
		<div class="coverframe-picture" style="background:url(<?php echo $movieThumbs; ?>) no-repeat center center; background-size:122px auto;">
		</div>
	</a>
	
	<div class="coverframe-text">
		<a href="episodedetails.php?id=<?php echo $showID; ?>"><?php echo "<b>".$showName." </b>-<br/>".$EpisodeName."<br/>(".$dateAdded.")"; ?></a>
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
        $footer_data .= "<a href='tvrecent.php?offset=".$val."'>Previous 20</a> | ";
    }
    if (count($result) > 0)
    {
        $footer_data .= "<a href='tvrecent.php?offset=".($offset+20)."'>Next 20</a>";
    }
    $footer_data .="</center>";
?>
<?php

	get_footer($footer_data);
?>

