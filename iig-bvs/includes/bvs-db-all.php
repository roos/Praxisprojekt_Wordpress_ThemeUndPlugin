<?php

// Diese Datei wird nicht ausgefuehrt, wenn sie direkt aufgerufen wird.
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

/**
* Diese Funktion speichert alle Werte der Honorar-Tabelle in einem assoziativen Array.
*
* @return array Es wird ein Array zurueck gegeben.
*/
function iig_bvs_get_all_data() {
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_honorar";

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name){
    $sql = $wpdb->get_results("SELECT * FROM " . $table_name, ARRAY_A); // ARRAY_A - result will be output as an associative array. (Quelle: https://codex.wordpress.org/Class_Reference/wpdb)
    return $sql;
  }
}

/**
* Diese Funktion speichert alle Werte der BVS-DIV-Tabelle in einem assoziativen Array.
*
* @return array Es wird ein Array zurueck gegeben.
*/
function iig_bvs_get_all_data_div() {
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_div";

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name){
    $sql = $wpdb->get_results("SELECT * FROM " . $table_name, ARRAY_A); // ARRAY_A - result will be output as an associative array. (Quelle: https://codex.wordpress.org/Class_Reference/wpdb)
    return $sql;
  }
}

/**
* Diese Funktion ermittelt einen Wert (Honorar, Wert, Bezeichnung, ...) und gibt diesen Wert zurueck.
*
* @param string $db_table: Es wird die Tabellenbezeichnung erwartet.
* @param int $id: Es wird eine ID erwartet.
* @param string $key: Es wird eine Spaltenbezeichnung erwartet.
*
* @return array Es wird ein String zurueck gegeben.
*/
function iig_bvs_get_data($db_table,$id,$key) {
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_" . $db_table;
  $sql = $wpdb->get_row("SELECT " . $key . " FROM " . $table_name . " WHERE id='" . $id . "';");

  return $sql->$key;
}

/**
* Diese Funktion ermittelt den Wert einer bestimmten ID und gibt diesen Wert zurueck.
*
* @param int $id: Es wird eine ID erwartet.
*
* @return int Es wird ein int-Wert zurueck gegeben.
*/
function iig_bvs_get_honorar_wert($id) {
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_honorar";

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name){
    $honorar = $wpdb->get_results("SELECT wert FROM " . $table_name . " WHERE id='" . $id . "';", ARRAY_A); // ARRAY_A - result will be output as an associative array. (Quelle: https://codex.wordpress.org/Class_Reference/wpdb)
    return $honorar[0]['wert'];
  }
}

/**
* Diese Funktion ermittelt das Honorar einer bestimmten ID und gibt diesen Wert zurueck.
*
* @param int $id: Es wird eine ID erwartet.
*
* @return int Es wird ein int-Wert zurueck gegeben.
*/
function iig_bvs_get_honorar($id) {
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_honorar";

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name){
    $honorar = $wpdb->get_results("SELECT honorar FROM " . $table_name . " WHERE id='" . $id . "';", ARRAY_A); // ARRAY_A - result will be output as an associative array. (Quelle: https://codex.wordpress.org/Class_Reference/wpdb)
    return $honorar[0]['honorar'];
  }
}

/**
* Diese Funktion ermittelt die Nebenkosten und gibt diesen Wert zurueck.
*
* @return int Es wird der int-Wert der Nebenkosten zurueck gegeben.
*/
function iig_bvs_get_div_nebenkosten() {
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_div";

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name){
    $nebenkosten = $wpdb->get_results("SELECT wert FROM " . $table_name . " WHERE bezeichnung='" . strtolower('Nebenkosten') . "';", ARRAY_A); // ARRAY_A - result will be output as an associative array. (Quelle: https://codex.wordpress.org/Class_Reference/wpdb)
    return $nebenkosten[0]['wert'];
  }
}

/**
* Diese Funktion ermittelt die Umsatzsteuer und gibt diesen Wert zurueck.
*
* @return int Es wird der int-Wert der Umsatzsteuer zurueck gegeben.
*/
function iig_bvs_get_div_mwst() {
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_div";

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name){
    $mwst = $wpdb->get_results("SELECT wert FROM " . $table_name . " WHERE bezeichnung='" . strtolower('Umsatzsteuer') . "';", ARRAY_A); // ARRAY_A - result will be output as an associative array. (Quelle: https://codex.wordpress.org/Class_Reference/wpdb)
    return $mwst[0]['wert'];
  }
}

?>
