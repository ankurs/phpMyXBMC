
<?php
	// If no id is specified in the url bar -> redirect to movieall.php
	if ($_GET['id'] == null) {
		header("Location: tvshowall.php");
		exit;
	}

	else {

		// Normal load of header and functions
		require_once('inc/functions.php');
		get_header();

		// The database connection and query of movie info
		$DBH = db_handle(DB_NAME_VIDEO);

		$sql = '
            SELECT * FROM `episodeview`
			WHERE idEpisode = :id 
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

            $rating = $row['c03'] + 0;
            $year = $row['c05'];

            $showID = $row['idShow'];
            $showName = $row['strTitle'];

            $movieThumbs = $row['c06'];
            if (!empty($movieThumbs))
            {
                $thumbXML = new SimpleXMLElement('<thumbs>'.$movieThumbs.'</thumbs>');
                $movieThumbs = $thumbXML->thumb[0];
            }
            else
            {
                $movieThumbs = "";
            }
            $path = $row['c18'];
            $path = str_replace('nfs://192.168.0.109/c/', 'http://'.$_SERVER['HTTP_HOST'].'/', $path);
		}
	}
?>

<h1><?php echo $movieTitle; ?> (<?php echo $year; ?>)</h1>
<h3> TV Series: <a href='tvshowdetails.php?id=<?php echo $showID; ?>'><?php echo $showName; ?></a></h3>
<h5>IMBD Rating: <?php echo $rating; ?></h5>
<h5><a href="<?php echo $path; ?>" target='_blank'>Download Episode (Right click -> Save link as..)</a></h5>

<div id="moviedetails-poster">
	<img src="<?php echo $movieThumbs; ?>" width='80%'><br/>
</div>
<br/>
<div id="moviedetails-plot">

	<div class="divider-medium"></div>
	Episode Info: <?php echo $moviePlot; ?>
	<div class="divider-medium"></div>
	
</div>

<?php
	get_footer();
?>
