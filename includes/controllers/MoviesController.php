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

    protected function renderRecent($STH, $offset)
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

                echo '<center> Recently Added Movies </center><br/>';
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

            $footer_data = "<center>";
            if ($offset > 0)
            {
                $val = ($offset - 20) > 0 ? ($offset - 20) : 0;
                $footer_data .= "<a href='".getURL('movies', 'recent', $val)."'>Previous 20</a> | ";
            }
            if (count($result) > 0)
            {
                $footer_data .= "<a href='".getURL('movies', 'recent', $offset+20)."'>Next 20</a>";
            }
            $footer_data .="</center>";

            get_footer($footer_data);

        }
}

?>

