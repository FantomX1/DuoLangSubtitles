# DuoLangSubtitles2  

Example:
 
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
  


// Result:   
// 1st row language from  
// 2nd row language to  

1
00:00:14,348 --> 00:00:17,725
♪♪[The|Entertainer|playing]
 ♪♪ [znaku | Entertainer | playing]

2
00:02:07,377 --> 00:02:09,295
[car|honking]
 [auto | kejhání]

3
00:02:34,404 --> 00:02:35,738
[all|chattering]
 [všetky | drnčanie]

4
00:02:35,823 --> 00:02:36,948
Let's|see|what|you|got.
 Poďme | vidieť | čo | ste | dostal.

