<?php
$genreInfo = array();
	// If no id is specified in the url bar -> redirect to movieall.php
	if ($_GET['id'] == null) {
		header("Location: movieall.php");
		exit;
	}

	else {

		// Normal load of header and functions
		require_once('inc/functions.php');
		get_header();

		// The database connection and query of movie info
        $DBH = db_handle(DB_NAME_VIDEO);

        // fetch genres
		$sql = '
            SELECT genre.idGenre, genre.strGenre
            FROM  `genre` ,  `genrelinkmovie` 
            WHERE genre.idGenre = genrelinkmovie.idGenre
            GROUP BY strGenre, idGenre
		';

		$STH = $DBH->prepare($sql);
		$STH->execute();

		$genres = $STH->fetchAll(PDO::FETCH_ASSOC);
        foreach($genres as $row) {
            $genreName = $row['strGenre'];
            $id = $row['idGenre'];
            $genreInfo[strtolower($genreName)] = $id;
        }
        // end fetch genre

		$sql = '
			SELECT * 
			FROM movieview
			WHERE idFile = :id 
			ORDER BY c00 ASC
		';

		$STH = $DBH->prepare($sql);
		$STH->execute( array(
			'id' => $_GET['id']
		));

		$result = $STH->fetchAll(PDO::FETCH_ASSOC);

		// Save all the important information in variables for use later in the HTML code
		foreach($result as $row) {
			$movieTitle = $row['c00'];
			$movieOutline = $row['c03'];
			$moviePlot = $row['c01'];
			$movieImdb = $row['c09'];

            $rating = $row['c05'] + 0;
            $year = $row['c07'] + 0;
            $genre = $row['c14'];
            $file = $row['c22'];

            $idFile = $row['idFile'];
            $filename = $row['strFileName'];
            $path = str_replace('nfs://192.168.0.109/c/', 'http://'.$_SERVER['HTTP_HOST'].'/', $row['strPath']);

            $trailerId = str_replace('plugin://plugin.video.youtube/?action=play_video&videoid=', '', $row['c19']);
            $trailer = 'http://www.youtube.com/watch?v='.$trailerId;

            $movieThumbs = $row['c20'];
            if (!empty($movieThumbs))
            {
                $thumbXML = new SimpleXMLElement($movieThumbs);
                $movieThumbs = $thumbXML->thumb[0];
            }
            else
            {
                $movieThumbs = "";
            }
		}
	}

    $genres = explode("/", $genre);
    $genre_vals = array();
    foreach ($genres as $genre)
    {
        $genre = trim($genre);
        if (isset($genreInfo[strtolower($genre)]))
        {
            $genre_vals[] = ' <a href="genreinfo.php?id='.$genreInfo[strtolower($genre)].'&genre='.$genre.'">'.$genre.'</a> ';
        }
    }
?>

<h1><a target='_blank' href='http://www.imdb.com/title/<?php echo $movieImdb; ?>' ><?php echo $movieTitle; ?></a> (<?php echo $year; ?>)</h1>
<h5><?php echo $movieOutline; ?></h5>
<h5>IMBD Rating: <?php echo $rating; ?></h5>
<h5>Genre: <?php echo implode("/", $genre_vals); ?></h5>
<h5><a href="<?php echo $path.$filename; ?>" target='_blank'>Download File</a> (Right Click -> Save Link As..)</h5>
<h5><a href="<?php echo $path; ?>" target='_blank'>Goto Folder</a></h5>
<h5><a target='_blank' href='http://www.imdb.com/title/<?php echo $movieImdb; ?>' >Click for IMDB Page</a></h5>
</h5>

<div id="moviedetails-poster">
    <center><iframe width="853" height="480" src="http://www.youtube.com/embed/<?php echo $trailerId; ?>" frameborder="0" allowfullscreen></iframe></center>
	<!--img src="<?php echo $movieThumbs; ?>" width='80%'><br/-->
</div>

<br/>

<div id="moviedetails-plot">
	<div class="divider-medium"></div>
	Movie Plot: <?php echo $moviePlot; ?>
	<div class="divider-medium"></div>
</div>


<?php
	get_footer();
?>
