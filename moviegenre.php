<?php
	// If no id is specified in the url bar -> redirect to movieall.php

		// Normal load of header and functions
		require_once('inc/functions.php');
		get_header();

		// The database connection and query of movie info
		$DBH = db_handle(DB_NAME_VIDEO);

		$sql = '
            SELECT genre.idGenre, genre.strGenre
            FROM  `genre` ,  `genrelinkmovie` 
            WHERE genre.idGenre = genrelinkmovie.idGenre
            GROUP BY strGenre, idGenre
		';

		$STH = $DBH->prepare($sql);
		$STH->execute();

		$result = $STH->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Search Movie by Genre</h2>
<?php
		// Save all the important information in variables for use later in the HTML code
        foreach($result as $row) {
            $genre = $row['strGenre'];
            $id = $row['idGenre'];

?>

<h4><a href='genreinfo.php?id=<?php echo $id."&genre=".$genre; ?>'/><?php echo $genre; ?></a></h5>

<?php
    }
	get_footer();
?>
