<?php
namespace Quiz\PHPDateTimeAgo\TextTranslator;

use Smirik\PHPDateTimeAgo\TextTranslator\AbstractTextTranslator;

class DutchTranslator extends AbstractTextTranslator
{
    protected $minute_words = array('minuut geleden', 'minuten geleden');
    protected $hour_words   = array('uur geleden', 'uur geleden');
    protected $day_words    = array('dag geleden', 'dagen geleden');

    /**
     * Pluralize the number according to the language. Returns key in related array (minute_words, hour_words, day_words)
     * @param integer $number
     * @return integer
     */
    protected function pluralization($number)
    {
        return ($number == 1) ? 0 : 1;
    }

    /**
     * Returns formatted "now" value
     * @return string
     */
    function now()
    {
        return 'zojuist';
    }
}
