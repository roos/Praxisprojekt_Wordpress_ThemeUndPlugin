<?php

// Diese Datei wird nicht ausgefuehrt, wenn sie direkt aufgerufen wird.
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

/**
* Diese Funktion formatiert einen int-Wert als Euro.
*
* @param int $var: Es wird eine zahl erwartet.
*
* @return string Es wird ein String zurueck gegeben.
*/
function iig_bvs_format_euro($var){
  return (number_format($var, 2, ',', '.') . " €");
}

/**
* Diese Funktion formatiert einen Wert anhand der Einstufung.
*
* Einstufungen:
* e = Euro
* f = Faktor
* p = Prozent
*
* @param int $var: Es wird eine Zahl erwartet.
* @param string $einstufung: Es wird ein Zeichen erwartet. (siehe Einstufungen)
*
* @return string Es wird ein String zurueck gegeben.
*/
function iig_bvs_format_div($var,$einstufung){
  if($einstufung == 'p'){
    $result = $result = (number_format($var, 0, ',', '.')) . " %";
  } elseif($einstufung == 'f'){
    $result = $result = (number_format($var, 1, ',', '.')) . "";
  } elseif($einstufung == 'e'){
    $result = $result = (number_format($var, 2, ',', '.')) . " €";
  }

  return $result;
}

?>
