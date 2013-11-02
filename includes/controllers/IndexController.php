<?php

class IndexController
{
    protected $smarty;

    public function __construct()
    {
    }

    public function defaultAction($params)
    {
        get_header();
        ?>

        <h1>Welcome To SatyaNAS</h1>
        <hr/>
        <br/>
        This is a collection of Movies/TV Shows hosted for Personal Use by/for people living with <a href='http://blog.ankurs.com' target='_blank'>OS</a><br/><br/>
        You can also access this server (<?php echo $_SERVER['HTTP_HOST']; ?>) over FTP on Port -> <?php echo FTP_PORT; ?><br/><br/>
        If you have access to this and you are NOT flatmate of <a href='http://blog.ankurs.com' target='_blank'>OS</a> please DO NOT Download/Stream Data or you will be held accountable<br/>
        <a href='http://who.is/whois-ip/ip-address/<?php echo $_SERVER['REMOTE_ADDR']; ?>' target='_blank' >HERE</a> is some part of information that is stored everytime you access SatyaNAS<br/>
        <br/>
        <!--PS - Database is being updated, please check back in an hour (curently a lot movies might have wrong tags)-->

        <?php
            $updates = get_updates();
            echo "<h4>$updates[0]</h4>";
        ?>

        <h4> Seagate Baracuda Green 2TB 3.5in Desktop HDD for sale, contact -> ankur[at]ankurs.com</h4>

        <?php
        get_footer();
    }
}

?>
