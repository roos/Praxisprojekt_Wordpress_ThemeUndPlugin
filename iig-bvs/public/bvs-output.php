<?php

// Diese Datei wird nicht ausgefuehrt, wenn sie direkt aufgerufen wird.
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

/**
* Diese Funktion erstellt ein Dropdown-Menue mit den Werten aus der Honorar-Tabelle.
*/
function iig_bvs_dropdown(){
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_honorar";
  $arr = iig_bvs_get_all_data();

  $output = "<form method='post'><select name='agent_id' id='myselect' dir='rtl' style='width:350px' onchange='if(this.value != 0) { this.form.submit(); }'>";
  if(isset($_POST['agent_id'])) {
    $agent_id = $_POST['agent_id'];
    $output .= "<option value='0'>" . iig_bvs_format_euro(iig_bvs_get_honorar_wert($_POST['agent_id'])) . "</option>";
  } else {
    $output .= "<option value='0'>" . 'Bitte treffen Sie Ihre Auswahl' . "</option>";
  }

  $output .= "<option disabled>_________</option>";

  foreach ($arr as $value) {
    $the_post = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE ID = " . $value["id"] . ";");
    $output .= "<option name='auswahl' value=" . $the_post->id . ">" . ((($the_post->optionen) != '') ? (($the_post->optionen == '<=') ? 'bis ' : 'über ') : '') . iig_bvs_format_euro($the_post->wert) . "</option>";
  }
  $output .= "</select><input id='aktualisieren' type='submit' value='Submit' style='display:none'></form>";

  return $output;
}

/**
* Diese Funktion gibt das Honorar in Brutto aus.
*
* @return string Es wird ein als String formatierter Betrag in EURO zurueck gegeben.
*/
function iig_bvs_ausgabe(){
  $ausgabe = '';
  if(isset($_POST['agent_id'])) {
    $agent_id = $_POST['agent_id'];
    $honorar = iig_bvs_get_honorar($agent_id);
    $ausgabe = iig_bvs_format_euro(iig_bvs_berechne_honorar_brutto($honorar));
  } else {
    $ausgabe = iig_bvs_format_euro(0);
  }
  return $ausgabe;
}

/**
* Diese Funktion berechnet das Honorar mit Nebenkosten und Umsatzsteuer.
*
* @param int $honorar: Es wird eine Zahl erwartet.
*
* @return int Es wird der Betrag mit Nebenkosten und Umsatzsteuer als Zahl zurueck gegeben.
*/
function iig_bvs_berechne_honorar_brutto($honorar) {
  $result = (($honorar * iig_bvs_get_div_nebenkosten())/100) + $honorar;
  $result = (($result * iig_bvs_get_div_mwst())/100) + $honorar;

  return $result;
}

// Shortcodes bei Wordpress registrieren.
add_shortcode('bvs','iig_bvs_dropdown');
add_shortcode('bvs-ausgabe','iig_bvs_ausgabe');

 ?>
