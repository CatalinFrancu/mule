<?php

class StringUtil {
  static function startsWith($string, $substring) {
    $startString = substr($string, 0, strlen($substring));
    return $startString == $substring;
  }

  static function endsWith($string, $substring) {
    $lenString = strlen($string);
    $lenSubstring = strlen($substring);
    $endString = substr($string, $lenString - $lenSubstring, $lenSubstring);
    return $endString == $substring;
  }

  static function charAt($s, $i) {
    return mb_substr($s, $i, 1);
  }

  static function randomCapitalLetters($length) {
    $result = '';
    for ($i = 0; $i < $length; $i++) {
      $result .= chr(rand(0, 25) + ord('A'));
    }
    return $result;
  }

  static function shortenString($s, $maxLength) {
    $l = mb_strlen($s);
    if ($l >= $maxLength) {
      return mb_substr($s, 0, $maxLength - 3) . '...';
    }
    return $s;
  }

  static function sanitize($s) {
    if (is_string($s)) {
      $s = trim($s);
      $s = str_replace(array("\r", 'ş', 'Ş', 'ţ', 'Ţ'), array('', 'ș', 'Ș', 'ț', 'Ț'), $s);
    }
    return $s;
  }
}

?>
