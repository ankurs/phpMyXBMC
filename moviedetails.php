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
			FROM movie 
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

        if (isset($idFile) && !empty($idFile))
        {
            $sql = '
                SELECT * 
                FROM files
                WHERE idFile = :id 
            ';

            $STH = $DBH->prepare($sql);
            $STH->execute( array(
                'id' => $_GET['id']
            ));

            $result = $STH->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row)
            {
                $filename = $row['strFilename'];
                $idPath = $row['idPath'];
            }
        }

        if (isset($idPath) && !empty($idPath))
        {
            $sql = '
                SELECT * 
                FROM path
                WHERE idPath = :id 
            ';

            $STH = $DBH->prepare($sql);
            $STH->execute( array(
                'id' => $idPath,
            ));

            $result = $STH->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result as $row)
            {
                $path = $row['strPath'];
                $path = str_replace('nfs://192.168.0.109/c/', 'http://media.satyanas.in/', $path);
            }
        }

	}
?>

<h1><a target='_blank' href='http://www.imdb.com/title/<?php echo $movieImdb; ?>' ><?php echo $movieTitle; ?></a> (<?php echo $year; ?>)</h1>
<h5><?php echo $movieOutline; ?></h5>
<h5>IMBD Rating: <?php echo $rating; ?></h5>
<h5>Genre: <?php echo $genre; ?></h5>
<h5><a href="<?php echo $path.$filename; ?>" target='_blank'>Download File</a></h5>
<h5><a href="<?php echo $path; ?>" target='_blank'>Goto Folder</a></h5>
<h5><a target='_blank' href='http://www.imdb.com/title/<?php echo $movieImdb; ?>' >Click for IMDB Page</a></h5>


<div id="moviedetails-poster">
	<img src="<?php echo $movieThumbs; ?>" width='80%'>
</div>

<div id="moviedetails-plot">

	<div class="divider-medium"></div>
	Movie Plot: <?php echo $moviePlot; ?>
	<div class="divider-medium"></div>
	
</div>

<?php
	get_footer();
?>
