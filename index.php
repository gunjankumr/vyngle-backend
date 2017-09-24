<?php

namespace FeedReader;

/* * 
 *  define the site path 
 *  
 */
$site_path = realpath(dirname(__FILE__));
define('__SITE_PATH', $site_path);
define('VIDEOS_PER_ROW', 4);

require_once 'Loader.php';

$loader = new Loader();
$loader->load();

$controller = new Controller\YoutubeVideoController(
                new Model\YoutubeVideoCollection(),
                new View\YoutubeView(),
                "http://gdata.youtube.com/feeds/api/videos?max-results=50&orderby=published&author=linkinparktv");
$controller->invoke();
?>
