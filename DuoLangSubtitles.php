<?php
/**
 * Created by PhpStorm.
 * User: fantomx1
 * Date: 30.9.2018
 * Time: 18:01
 */

namespace FantomX1;

class DuoLangSubtitles {

    CONST RESULT_FILE="result.srt";

    /**
     * @var
     */
    private $originalFile;

    /**
     * @var
     */
    private $from;

    /**
     * @var
     */
    private $to;

    private $defaultBatchSize = 500;

    private $resultFile;


    /**
     * duoLangSubtitles constructor.
     */
    public function __construct()
    {
        // default translation
        $this->from = 'en';
        $this->to = 'sk';
        $this->resultFile = self::RESULT_FILE;
    }

    public function getResultFile()
    {
        file_get_contents($this->resultFile);
    }


    /**
     * @param $file
     */
    public function setOriginalSubtitlesFile($file)
    {
        $this->originalFile =  $file;
    }

    /**
     * @param $from
     * @param $to
     */
    public function setFromTo($from, $to)
    {
        $this->from;
        $this->to;
    }

    /**
     * @param $batchSize
     */
    public function setBatchSize($batchSize)
    {
        $this->defaultBatchSize = $batchSize;
    }

    public function setResultFile($file)
    {

        $this->resultFile = $file;
    }

    /**
     *
     */
    public function process()
    {

        $fileRows                  = file($this->originalFile);
        $batchesTextAndAnnotations = $this->parseSrtIntoGtranslateBatches($fileRows);
        $translatedStrut           = $this->translateBatchesIntoText($batchesTextAndAnnotations);
        $rows = $this->formatIntoSynchronizedRows(
            $translatedStrut,
            $batchesTextAndAnnotations['technicalRowKeys']
        );

        // export into setted, constructor defaulted result file
        file_put_contents($this->resultFile,
            implode("\n", $rows)
        );
    }

    /**
     * @param $fileRows
     * @return array ['textInBatches'=>, 'technicalRowKeys']
     */
    private function parseSrtIntoGtranslateBatches($fileRows)
    {
        $textInBatches             = [];
        $translateBatchId = 1;
        $technicalRowKeys = [];

        foreach ($fileRows as $rowKey => $row) {

            if ($rowKey % $this->defaultBatchSize == 0) {
                $translateBatchId++;
            }


            if ($this->isRowNonSubtitlePivotalSchema($row)) {
                $textInBatches[$translateBatchId] = $textInBatches[$translateBatchId] . '^';

                $technicalRowKeys[$rowKey] = $row . '';

                continue;
            }

            // adjust text , replace spaces with | so words are translated separately , not as a sentence
            // so they correspond to each word in original language in order
            $row                     = str_replace(' ', '|', $row);
            $textInBatches[$translateBatchId] = $textInBatches[$translateBatchId] . '' . $row . '^';
        }

        return ['textInBatches'=>$textInBatches, 'technicalRowKeys' => $technicalRowKeys];
    }

    /**
     * @param $row
     * @return bool
     */
    private function isRowNonSubtitlePivotalSchema($row)
    {
        return is_numeric($row[0]) OR !$row;
    }


    /**
     * @param $batchesTextAndAnnotations
     * @return array
     */
    private function translateBatchesIntoText($batchesTextAndAnnotations)
    {
        // default en -> sk
        $tr = new TranslateClient($this->from, $this->to);

        $fullText = '';
        $fullTextOrigi = '';
        foreach($batchesTextAndAnnotations as &$textItem) {

            $fullText .=$tr->translate($textItem);
            $fullTextOrigi .=$textItem;
        }

        return [
            "fullText" => $fullText,
            "fullTextOrigi" => $fullTextOrigi,
        ];



    }

    /**
     * @param $translatedStrut
     * @return array
     */
    private function formatIntoSynchronizedRows($translatedStrut, $rowKeys)
    {

        $fullTextTranslated = $translatedStrut['fullText'];
        $fullTextOrigi      = $translatedStrut['fullTextOrigi'];

        $textArrTranslated = explode('^', $fullTextTranslated);
        $textArrOrigi      = explode('^', $fullTextOrigi);

        // synchronize and merge keys of original with translations
        foreach ($textArrOrigi as $key => &$textArrItemOrigi) {
            $itemTranslated   = $textArrTranslated[$key] ?? 0;
            $textArrItemOrigi .= "" . $itemTranslated;

        }

        $rows = $rowKeys + $textArrOrigi;

        ksort($rows);

        array_walk($rows, function(&$item){
            $item = trim($item);
        });

        return $rows;
    }


}