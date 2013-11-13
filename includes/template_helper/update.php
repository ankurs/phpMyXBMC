<?php
$host = $_SERVER['SERVER_NAME'];
$updates = array(
        "13th Nov 2013 10:20pm : Competed rewrite of SatyaNAS, more features coming soon",
        "4th June 2013 11:00pm : FINISHED volume expansion from 2TB to 3TB complete, we now have 3TB RAID 1 volumes this should result in better read performance",
        "4th June 2013 11:00pm : STARTED volume expansion from 2TB to 3TB SatyaNAS might be down for 5 mins",
        "4th June 2013 11:00pm : FINISHED making SatyaNAS data redundant (RAID 1) copy to secondary HDD",
        "4th June 2013 5:30pm : STARTED making SatyaNAS data redundant (RAID 1) copy to secondary HDD",
        "26th May 2013: Rescan of TV Shows for better automated way of managing TV Shows and Rearranged Music section",
        "23rd May 2013: New -><a href='http://{$host}:8001/mpd.ogg'>Streaming Music Service</a>",
        "28th April 2013: New -> <a href='".getURL('tvshows', 'recent')."'>Recently Added TV Episodes section</a>",
        "18th April 2013: New -> <a href='".getURL('movies', 'top')."'>Movies by IMDB Rating section</a>",
        "13th April 2013: Movie Trailers instead of Fanart images",
        "13th April 2013: NEW -> <a href='".getURL('movies', 'genre')."'>Movies by Genre Section</a>",
        "10th April 2013 (2:30AM): YAY was able to recover all the data from my old drive, SatyaNAS is back :) (PS: LVM with RAID is fun until something goes wrong :p )",
        "9th April 2013: Got a new 3TB HDD trying to fix the issues, looks like superblock on my main ext4 volume is curropt, trying to recover as much data as possible",
        "8th April 2013: Terible Disk failure, i am seeing SMART errors on my single 2TB disk, SatyaNAS is down :(",
        "28th March 2013: Updated SatyaNAS RAM to 2GB, should result is better performance :)",
        "16th March 2013: NEW -> <a href='".getURL('movies', 'recent')."'>Recently Added Movies Section</a>",
        "3rd March 2013: Better Bandwidth -> 25 Mb/s Up and Down, yay!",
    );


?>
