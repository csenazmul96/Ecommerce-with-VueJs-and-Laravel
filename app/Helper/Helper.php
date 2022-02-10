<?php

    function changeSpecialChar($text) {
        $text = str_replace(' ', '-', $text);

        return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $text));
    }

    function integerToText($x) {
        $nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven",
            "eight", "nine", "ten", "eleven", "twelve", "thirteen",
            "fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
            "nineteen", "twenty", 30 => "thirty", 40 => "forty",
            50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
            90 => "ninety" );

        if(!is_numeric($x))
            $w = '#';
        else if(fmod($x, 1) != 0)
            $w = '#';
        else {
            if($x < 0) {
                $w = 'minus ';
                $x = -$x;
            } else
                $w = '';
            // ... now $x is a non-negative integer.

            if($x < 21)   // 0 to 20
                $w .= $nwords[$x];
            else if($x < 100) {   // 21 to 99
                $w .= $nwords[10 * floor($x/10)];
                $r = fmod($x, 10);
                if($r > 0)
                    $w .= '-'. $nwords[$r];
            } else if($x < 1000) {   // 100 to 999
                $w .= $nwords[floor($x/100)] .' hundred';
                $r = fmod($x, 100);
                if($r > 0)
                    $w .= ' and '. int_to_words($r);
            } else if($x < 1000000) {   // 1000 to 999999
                $w .= int_to_words(floor($x/1000)) .' thousand';
                $r = fmod($x, 1000);
                if($r > 0) {
                    $w .= ' ';
                    if($r < 100)
                        $w .= 'and ';
                    $w .= int_to_words($r);
                }
            } else {    //  millions
                $w .= int_to_words(floor($x/1000000)) .' million';
                $r = fmod($x, 1000000);
                if($r > 0) {
                    $w .= ' ';
                    if($r < 100)
                        $word .= 'and ';
                    $w .= int_to_words($r);
                }
            }
        }
        return $w;
    }

//if (! function_exists('pr')) {
//	/**
//	 * Dump the passed variables and end the script.
//	 *
//	 * @param  mixed  $args
//	 * @return void
//	 */
//	function pr(...$args)
//	{
//		foreach ($args as $x) {
//			echo '<pre>';
//			print_r($x);
//			echo '</pre>';
//		}
//
//		die(1);
//	}
//}