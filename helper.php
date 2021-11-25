<?php


class helper
{
    public function num2db($number) : string {
        $number = trim($number);

        if ($number == '') {
            return 'NULL';
        }

        return str_replace(',', '.', $number);
    }

    public function escape_field($text): string {
        $text    = trim($text);
        $search  = array('\\', '\""', '""', '\"', "'");
        $replace = array('\\\\', '\"', '\"', '\"', "\'");

        if ($text == "" || $text == " ") {
            return 'NULL';
        }

        return "'" . str_replace($search, $replace, $text) . "'";
    }
}