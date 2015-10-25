<?php

class TvshowsController
{
    public function defaultAction($params)
    {
        $sql = "
            SELECT * 
            FROM tvshow 
            ORDER BY c00 ASC
        ";

        $DBH = db_handle(DB_NAME_VIDEO);
        $STH = $DBH->prepare($sql);
        $STH->execute();
	    $this->renderTvShows($STH);
    }

    protected function renderTvShows($STH)
    {
        get_header();
        $result = $STH->fetchAll();
        foreach($result as $row) {
                $movieThumbs = $row['c06'];
                if (!empty($movieThumbs))
                {
                    $thumbXML = new SimpleXMLElement('<thumbs>'.$movieThumbs.'</thumbs>');
                    $movieThumbs = $thumbXML->thumb[0];
                    foreach($thumbXML->thumb as $thumb)
                    {
                        if ($thumb['type'] == 'season' && $thumb['season'] == '-1')
                        {
                            $movieThumbs = $thumb.'';
                            break;
                        }
                    }
                }
                else
                {
                    $movieThumbs = "";
                }
            echo '
                <div class="coverframe">
                <a href="'.getURL('tvshows', 'details', $row['idShow']).'">
                <div class="coverframe-picture" style="background:url('.$movieThumbs.') no-repeat center center; background-size:122px auto;">
                    </div></a>
                    <div class="coverframe-text">
                        <a href="'.getURL('tvshows', 'details', $row['idShow']).'">' . $row['c00'] . '</a>
                    </div>
                </div>
            ';
        }
        get_footer();
    }

    public function detailsAction($params)
    {
        if (count($params) < 1)
        {
            $this->defaultAction($params);
            return;
        }
        $id = $params[0];
		// The database connection and query of movie info
		$DBH = db_handle(DB_NAME_VIDEO);

		$sql = '
			SELECT * 
			FROM tvshow_view
			WHERE idShow = :id 
			ORDER BY c00 ASC
		';

		$STH = $DBH->prepare($sql);
		$STH->execute( array(
			'id' => $id
        ));
        $this->renderTvShowDetails($STH);
    }

    protected function renderTvShowDetails($STH)
    {
        get_header();

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
            $path = $row['strPath'];
            $path = getPATH($path);
		}
        ?>

        <h1><a target='_blank' href='http://thetvdb.com/?tab=series&id=<?php echo $movieImdb; ?>' ><?php echo $movieTitle; ?></a> (<?php echo $year; ?>)</h1>
        <h5>IMBD Rating: <?php echo $rating; ?></h5>
        <h5>Genre: <?php echo $genre; ?></h5>
        <h5><a href='<?php echo $path; ?>' target='_blank'>Goto Folder</a></h5>
        <h5><a target='_blank' href='http://thetvdb.com/?tab=series&id=<?php echo $movieImdb; ?>' >Click for TVDB page</a></h5>

        <div id="moviedetails-poster">
            <img src="<?php echo $movieThumbs; ?>" width='80%'><br/>
        </div>
        <br/>
        <div id="moviedetails-plot">

            <div class="divider-medium"></div>
            Series Plot: <?php echo $moviePlot; ?>
            <div class="divider-medium"></div>
        </div>
        <?php
        get_footer();

    }

    public function recentAction($params)
    {
        $offset = 0;
        if ((count($params) > 0) && is_numeric($params[0]))
        {
            $offset = $params[0];
        }
        $sql = "
            SELECT * FROM `episode_view`
            ORDER BY `dateAdded` DESC LIMIT 20 OFFSET :offset
        ";

        // The database connection
        $DBH = db_handle();
        $STH = $DBH->prepare($sql);
        $STH->execute( array('offset' => $offset) );
        $this->renderRecent($STH, $offset);
    }

    protected function renderRecent($STH, $offset)
    {
        get_header();
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
            
            <a href="<?php echo getURL('tvshows', 'episode', $showID); ?>">
                <div class="coverframe-picture" style="background:url(<?php echo $movieThumbs; ?>) no-repeat center center; background-size:122px auto;">
                </div>
            </a>
            
            <div class="coverframe-text">
                <a href="<?php echo getURL('tvshows', 'episode', $showID); ?>"><?php echo "<b>".$showName." </b>-<br/>".$EpisodeName."<br/>(".$dateAdded.")"; ?></a>
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
                $footer_data .= "<a href='".getURL('tvshows', 'recent', $val)."'>Previous 20</a> | ";
            }
            if (count($result) > 0)
            {
                $footer_data .= "<a href='".getURL('tvshows', 'recent', $offset+20)."'>Next 20</a>";
            }
            $footer_data .="</center>";
        ?>
        <?php

        get_footer($footer_data);
    }

    public function episodeAction($params)
    {
        if (count($params) > 0 && is_numeric($params[0]))
        {
            $episodeId = $params[0];
        }
        else
        {
            $this->defaultAction($params);
            return;
        }

		$DBH = db_handle(DB_NAME_VIDEO);

		$sql = '
            SELECT * FROM `episode_view`
			WHERE idEpisode = :id 
		';

		$STH = $DBH->prepare($sql);
		$STH->execute( array(
            'id' => $episodeId
        ));
        $this->renderEpisode($STH);
    }

    protected function renderEpisode($STH)
    {
        get_header();

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
            $path = getPath($row['c18']);
		}
        ?>

        <h1><?php echo $movieTitle; ?> (<?php echo $year; ?>)</h1>
        <h3> TV Series: <a href='<?php echo getURL('tvshows','details', $showID); ?>'><?php echo $showName; ?></a></h3>
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

    }
}

?>
