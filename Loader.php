<?php

namespace FeedReader;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Loader
 *
 * @author shresthap
 */
class Loader {

    public function load() {
//        set_include_path(implode(PATH_SEPARATOR, array('FeedReader', 'XmlParser', 'Controller', 'View', 'Model')));
//        set_include_path(__DIR__);
//        spl_autoload_extensions('.php');
//        spl_autoload_register();
        spl_autoload_register(function() {
                    require_once "Controller/YoutubeVideoController.php";
                    require_once "Model/IVideoCollection.php";
                    require_once "Model/AbstractVideoCollection.php";
                    require_once "Model/YoutubeVideoCollection.php";
                    require_once "View/YoutubeView.php";
                    require_once "Model/IVideo.php";
                    require_once "Model/Video.php";
                    require_once "Model/YoutubeVideo.php";
                    require_once "XmlParser/IParser.php";
                    require_once "XmlParser/XmlParser.php";
                });
    }

}

?>
