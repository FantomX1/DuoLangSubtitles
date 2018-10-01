<?php
/**
 * Created by PhpStorm.
 * User: fantomx1
 * Date: 29.9.2018
 * Time: 14:24
 */

require 'vendor/autoload.php';

use Stichoza\GoogleTranslate\TranslateClient;

$tr = new TranslateClient('en', 'sk');

set_time_limit(3600*3);
$contents = file("theSting.srt");

$elements = [];

$words = [];


$text             = [];
$translateBatchId = 1;

foreach ($contents as $rowKey => $row) {

    if ($rowKey % 500 == 0) {
        $translateBatchId++;
    }


    if (is_numeric($row[0]) OR !$row) {
        $text[$translateBatchId] = $text[$translateBatchId] . '^';

        $rowKeys[$rowKey] = $row . '';

        continue;
    }

    $row                     = str_replace(' ', '|', $row);
    $text[$translateBatchId] = $text[$translateBatchId] . '' . $row . '^';;
}


##############

$fullText = '';
$fullTextOrigi = '';
foreach($text as &$textItem) {

    $fullText .=$tr->translate($textItem);
    //$fullText .=$textItem;
    $fullTextOrigi .=$textItem;
}




$texta = explode('^', $fullText);
$textaOrigi = explode('^', $fullTextOrigi);


//$pom = $texta;
//$pom = $rowKeys+$texta;

foreach ($textaOrigi as $key=>&$textaItem) {
    $text = $texta[$key] ?? 0;
    $textaItem .= "".$text;

}

$pom = $rowKeys+$textaOrigi;




ksort($pom);

//var_dump($pom); die();


 array_walk($pom, function(&$item){
     $item = trim($item);

 });


file_put_contents("oky.srt",
    implode("\n", $pom)
//var_export($texta, true)
);