<?php

class MoviesController
{
    protected $smarty;

    public function __construct()
    {
    }

    public function defaultAction()
    {
        $alphabet = range('a', 'z');
        $sql = "
                SELECT idFile, c00, strPath, playCount, c08
                FROM movieview
                WHERE ";
                foreach($alphabet as $letter)
                {
                    $and = 'and';
                    if ($letter == 'z')
                        $and='';
                    $sql.= " LEFT(c00,1) !='".$letter."' $and
                        ";
                }
                $sql.="ORDER BY c00 ASC";
             // The database connection
            $DBH = db_handle();
            $STH = $DBH->prepare($sql);
            $STH->execute( );
            $this->renderMovie($STH);
    }

    public function allAction()
    {
        $sql = "
                SELECT idFile, c00, strPath, playCount, c08
                FROM movieview 
                ORDER BY c00 ASC
            ";

        // The database connection
        $DBH = db_handle();
        $STH = $DBH->prepare($sql);
        $STH->execute( );
        $this->renderMovie($STH);
    }

    public function charAction($params)
    {
        $sel_char = 'a';
        if (count($params) > 0)
        {
            $sel_char = $params[0];
        }
        $sql = "
                SELECT idFile, c00, strPath, playCount, c08
                FROM movieview 
                WHERE LEFT(c00,1) = :char 
                ORDER BY c00 ASC
            ";

        // The database connection
        $DBH = db_handle();
        $STH = $DBH->prepare($sql);
        $STH->execute( array('char' => $sel_char) );
        $this->renderMovie($STH);
    }

    protected function renderMovie($STH)
    {
        get_header();
        ?>
        <div class="movieall-charchooser">

            <?php
                // This prints out links to all the movies beginning with the first letter of the alphabet
                $alphabet = range('a', 'z');

        echo '<a href="'.getURL('movies', 'all').'">(Show All)</a>';
        echo '<a href="'.getURL('movies').'">#</a>';
                foreach($alphabet as $letter) {
                    echo '<a href="'.getURL('movies', 'char', $letter) . '">' . strtoupper($letter) . '</a>';
                }
            ?>
        <hr/>
            <div class="clear"></div>
        </div>

        <?php
                // Fetches the array of each movie and saves in another array.
                // An array named $result containing an array for each movie.
                $result = $STH->fetchAll(PDO::FETCH_ASSOC);

                echo '<center> Found '.count($result).' Movies</center><br/>';
                // Loops through the array and prints out information for every movie
                for($i = 0, $size = sizeof($result); $i < $size; ++$i) {

                    $movieID = $result[$i]["idFile"];
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

            <a href="<?php echo getURL('movies', 'details', $movieID); ?>">
                <div class="coverframe-picture" style="background:url(<?php echo $movieThumbs; ?>) no-repeat center center; background-size:122px auto;">
                </div>
            </a>

            <div class="coverframe-text">
                <a href="<?php echo getURL('movies', 'details', $movieID); ?>"><?php echo $movieName; ?></a>
            </div>

        </div>

        <?php
        }
        get_footer();
    }

    public function topAction($params)
    {
        $offset = 0;
        if ((count($params) > 0) && is_numeric($params[0]))
        {
            $offset = $params[0];
        }
        $sql = "
                SELECT idFile, c00, strPath, playCount, c08, c05
                FROM movieview 
                ORDER BY `c05` DESC LIMIT 20 OFFSET :offset
            ";

        // The database connection
        $DBH = db_handle();
        $STH = $DBH->prepare($sql);
        $STH->execute( array('offset' => $offset) );
        $this->renderRecent($STH, $offset, true);
    }

    public function recentAction($params)
    {
        $offset = 0;
        if ((count($params) > 0) && is_numeric($params[0]))
        {
            $offset = $params[0];
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
        $this->renderRecent($STH, $offset);
    }

    protected function renderRecent($STH, $offset, $top=false)
    {
        get_header();
        ?>
        <div class="movieall-charchooser">
        <hr/>
            <div class="clear"></div>
        </div>
        <?php
                // Fetches the array of each movie and saves in another array.
                // An array named $result containing an array for each movie.
                $result = $STH->fetchAll(PDO::FETCH_ASSOC);

                if (!$top)
                {
                    echo '<center> Recently Added Movies </center><br/>';
                }
                else
                {
                    echo '<center>Movies By IMDB Rating</center><br/>';
                }
                // Loops through the array and prints out information for every movie
                for($i = 0, $size = sizeof($result); $i < $size; ++$i) {
                    $movieID = $result[$i]["idFile"];
                    $movieName = $result[$i]['c00'];
                    $moviePlayed = $result[$i]['playCount'];
                    $movieThumbs = $result[$i]['c08'];
                    $rating = $result[$i]['c05'] + 0;

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
            <a href="<?php echo getURL('movies', 'details', $movieID); ?>">
                <div class="coverframe-picture" style="background:url(<?php echo $movieThumbs; ?>) no-repeat center center; background-size:122px auto;">
                </div>
            </a>
            <div class="coverframe-text">
                <a href="<?php echo getURL('movies', 'details', $movieID); ?>"><?php echo $movieName; if($top) echo '<br/>IMDB: '.$rating; ?></a>
            </div>
        </div>
        <?php
            }
            $action = 'recent';
            if ($top)
            {
                $action = 'top';
            }
            $footer_data = "<center>";
            if ($offset > 0)
            {
                $val = ($offset - 20) > 0 ? ($offset - 20) : 0;
                $footer_data .= "<a href='".getURL('movies', $action, $val)."'>Previous 20</a> | ";
            }
            if (count($result) > 0)
            {
                $footer_data .= "<a href='".getURL('movies', $action, $offset+20)."'>Next 20</a>";
            }
            $footer_data .="</center>";

            get_footer($footer_data);

        }

        public function detailsAction($params)
        {
            if (count($params) > 0 && is_numeric($params[0]))
            {
                $movieId = $params[0];
            }
            else
            {
                header("Location: ".getURL('movies'));
                return;
            }
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
                'id' => $movieId
            ));

            $this->renderDetails($STH, $genreInfo);

        }

    protected function renderDetails($STH, $genreInfo)
    {
        get_header();

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
                $path = getPath($row['strPath']);

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
            $genres = explode("/", $genre);
            $genre_vals = array();
            foreach ($genres as $genre)
            {
                $genre = trim($genre);
                if (isset($genreInfo[strtolower($genre)]))
                {
                    $genre_vals[] = ' <a href="'.getURL('movies', 'genreInfo', array($genreInfo[strtolower($genre)], $genre)).'">'.$genre.'</a> ';
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
    }

    public function genreAction($params)
    {
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
            <h4><a href='<?php echo getURL('movies', 'genreInfo', array($id, $genre) )."'/>".$genre; ?></a></h5>
            <?php
            }
        get_footer();
    }

    public function genreInfoAction($params)
    {
        if (count($params) > 0 && is_numeric($params[0]))
        {
            $id = $params[0];
        }
        else
        {
            header("Location: ".getURL('movies', 'genre'));
            return;
        }
        $genre = null;
        if (count($params) > 1)
        {
            $genre = $params[1];
        }
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
        $STH->execute( array('id' => $id));
        $this->rendergenreInfo($STH, $genre);
    }

    protected function rendergenreInfo($STH, $genre)
    {
        get_header();
		// Fetches the array of each movie and saves in another array.
		// An array named $result containing an array for each movie.
		$result = $STH->fetchAll(PDO::FETCH_ASSOC);

            echo '<center> Found '.count($result).' Movies for genre -> '.$genre.'</center><br/>';
		// Loops through the array and prints out information for every movie
		for($i = 0, $size = sizeof($result); $i < $size; ++$i) {
			
			$movieID = $result[$i]["idFile"];
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
	
	<a href="<?php echo getURL('movies', 'details', $movieID); ?>">
		<div class="coverframe-picture" style="background:url(<?php echo $movieThumbs; ?>) no-repeat center center; background-size:122px auto;">
		</div>
	</a>
	
	<div class="coverframe-text">
		<a href="<?php echo getURL('movies', 'details', $movieID); ?>"><?php echo $movieName; ?></a>
	</div>

</div>

<?php
    }
	get_footer();

    }
}

?>

