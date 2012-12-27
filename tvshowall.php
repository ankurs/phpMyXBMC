<?php
	require_once('inc/functions.php');
	get_header();
?>

<h1>All TV Shows</h1>
<div class="divider-large"></div>

<?php

	$sql = "
		SELECT * 
		FROM tvshow 
		ORDER BY c00 ASC
	";

	$DBH = db_handle(DB_NAME_VIDEO);
	$STH = $DBH->prepare($sql);
	$STH->execute();
	
	$result = $STH->fetchAll();
	
	
	
	foreach($result as $row) {
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
		echo '
			<div class="coverframe">
            <a href="tvshowdetails.php?id=' . $row['idShow'] . '">
            <div class="coverframe-picture" style="background:url('.$movieThumbs.') no-repeat center center; background-size:122px auto;">
				</div></a>
				<div class="coverframe-text">
					<a href="tvshowdetails.php?id=' . $row['idShow'] . '">' . $row['c00'] . '</a>
				</div>
			</div>
		';
	}

?>


<?php
	get_footer();
?>
