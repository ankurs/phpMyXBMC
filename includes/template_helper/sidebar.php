<div id="sidebar">

	<div id="nav">
		<ul>
			<li><a href="/" >Home</a></li>
            <li><a href='<?php echo getURL('movies');?>' >All Movies</a></li>
            <li><a href='<?php echo getURL('movies','recent');?>' >Recently Added Movies</a></li>
            <li><a href='<?php echo getURL('movies', 'top');?>' >Movies By IMDB Rating</a></li>
            <li><a href='<?php echo getURL('movies','genre');?>' >Movies By Genre</a></li>
			<li><a href='<?php echo getURL('tvshows');?>' >TV Shows</a></li>
			<li><a href='<?php echo getURL('tvshows', 'recent');?>' >Recently Added TV Episodes</a></li>
            <li><a href='ftp://<?php echo $_SERVER['SERVER_NAME'];?>:2112/media/'>Browse Folders</a></li>
            <li><a href='http://<?php echo $_SERVER['SERVER_NAME'];?>:8001/mpd.ogg'>Streaming Music Service</a></li>
            <li><a href='<?php echo getURL('updates');?>'>SatyaNAS Updates</a></li>
<?php
        if ($_SERVER["REMOTE_USER"] != 'hakuna')
        {
            echo '<li><a href="http://'.$_SERVER['SERVER_NAME'].':8181"/>Torrent Control</a></li>';
        }
?>
		</ul>
	</div>

</div>
