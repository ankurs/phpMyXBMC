<?php
	require_once('inc/functions.php');
	get_header();
?>

 
<?php

         $sql = "
             SELECT idFile, c00, strPath, playCount, c08
             FROM movieview
             WHERE idMovie
             IN (

                 SELECT idMovie
                 FROM genrelinkmovie
                 WHERE idGenre = :id
             )
             Order BY c00 ASC
                ";

            // The database connection
            $DBH = db_handle();
            $STH = $DBH->prepare($sql);
            $STH->execute( array('id' => $_GET['id']));

		// Fetches the array of each movie and saves in another array.
		// An array named $result containing an array for each movie.
		$result = $STH->fetchAll(PDO::FETCH_ASSOC);

            echo '<center> Found '.count($result).' Movies for genre -> '.$_GET['genre'].'</center><br/>';
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
