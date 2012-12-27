
<?php
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

		$sql = '
			SELECT * 
			FROM tvshow 
			WHERE idShow = :id 
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
			$moviePlot = $row['c01'];
			$movieImdb = $row['c12'];

            $rating = $row['c04'] + 0;
            $year = $row['c05'];
            $genre = $row['c08'];

            $movieThumbs = $row['c11'];
            if (!empty($movieThumbs))
            {
                $thumbXML = new SimpleXMLElement('<thumb>'.$movieThumbs.'</thumb>');
                $movieThumbs = $thumbXML->fanart['url'];
                $movieThumbs .= $thumbXML->fanart->thumb[0];
            }
            else
            {
                $movieThumbs = "";
            }
            $path = $row['c16'];
            $path = str_replace('nfs://192.168.0.109/c/', 'http://media.satyanas.in/', $path);
		}
	}
?>

<h1><a target='_blank' href='http://thetvdb.com/?tab=series&id=<?php echo $movieImdb; ?>' ><?php echo $movieTitle; ?></a> (<?php echo $year; ?>)</h1>
<h5>IMBD Rating: <?php echo $rating; ?></h5>
<h5>Genre: <?php echo $genre; ?></h5>
<h5><a href="<?php echo $path; ?>" target='_blank'>Goto Folder</a></h5>
<h5><a target='_blank' href='http://thetvdb.com/?tab=series&id=<?php echo $movieImdb; ?>' >Click for TVDB page</a></h5>

<div id="moviedetails-poster">
	<img src="<?php echo $movieThumbs; ?>" width='80%'>
</div>

<div id="moviedetails-plot">

	<div class="divider-medium"></div>
	Series Plot: <?php echo $moviePlot; ?>
	<div class="divider-medium"></div>
	
</div>

<?php
	get_footer();
?>
