<div id="sidebar">
	
	<div id="nav">
		<ul>
			<li><a href="/" >Home</a></li>
            <li><a href='movieall.php?char=0' >All Movies</a></li>
            <li><a href='movierecent.php' >Recently Added Movies</a></li>
			<li><a href='tvshowall.php' >TV Shows</a></li>
            <li><a href='../'>Browse Folders</a></li>
            <li><a href='updates.php'>SatyaNAS Updates</a></li>
<?php
        if ($_SERVER["REMOTE_USER"] != 'hakuna')
        {
            echo '<li><a href="http://'.$_SERVER['SERVER_NAME'].':8181"/>Torrent Control</a></li>';
        }
?>
		</ul>
	</div>

</div>
