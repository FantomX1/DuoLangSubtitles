<?php
/**
 * Created by PhpStorm.
 * User: fantomx1
 * Date: 1.10.2018
 * Time: 8:28
 */

use FantomX1\DuoLangSubtitles;


$subtitleMerger = new DuoLangSubtitles();
$subtitleMerger->setOriginalSubtitlesFile('The.Sting.1973.Blu-ray.23.976.x264-Mathandler.srt');
$subtitleMerger->setFromTo('en','sk');
// batch size for google translate api
//$subtitleMerger->setBatchSize(500);
//$subtitleMerger->setResultFile('result.srt')
$subtitleMerger->process();

var_dump(
    $subtitleMerger->getResultFile()
);

