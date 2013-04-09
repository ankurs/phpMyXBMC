<?php
	require_once('inc/functions.php');
	get_header();
?>

<h1>Updates for SatyaNAS</h1>
<br/>
<h4> 10th April 2013 (2:30AM): YAY was able to recover all the data from my old drive, SatyaNAS is back :) (PS: LVM with RAID is fun until something goes wrong :p )</h4>
<h4> 9th April 2013: Got a new 3TB HDD trying to fix the issues, looks like superblock on my main ext4 volume is curropt, trying to recover as much data as possible </h4>
<h4> 8th April 2013: Terible Disk failure, i am seeing SMART errors on my single 2TB disk, SatyaNAS is down :(</h4>
<h4> 28th March 2013: Updated SatyaNAS RAM to 2GB, should result is better performance :)</h4>
<h4> 16th March 2013: NEW -> <a href='movierecent.php'>Recently Added Movies Section</a></h4>
<h4> 3rd March 2013: Better Bandwidth -> 25 Mb/s Up and Down, yay!</h4>
<br/>

<?php
	get_footer();
?>
