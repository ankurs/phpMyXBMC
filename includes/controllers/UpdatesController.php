<?php

class UpdatesController
{
    public function defaultAction($params)
    {
        get_header();
        ?>
        <h1>Updates for SatyaNAS</h1>
        <br/>
        <?php
            foreach(get_updates() as $update)
            {
                echo "<h4>$update</h4>";
            }
        ?>
        <br/>
        <?php
        get_footer();
    }
}


