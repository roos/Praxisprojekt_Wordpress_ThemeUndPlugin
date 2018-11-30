<?php

// Diese Datei wird nicht ausgefuehrt, wenn sie direkt aufgerufen wird.
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

/**
* Diese Funktion stellt eine eigene CSS-Datei bereit.
*/
function iig_bvs_css_overview_script() {
   $url = plugin_dir_url( __FILE__ ) . 'css/admin.css';
   wp_register_style( 'overview-styles',  $url);
   wp_enqueue_style( 'overview-styles' );
}
add_action('admin_enqueue_scripts', 'iig_bvs_css_overview_script');

/**
* Diese Funktion wird direkt wird direkt aus dem Adminmenue aufgerufen.
*/
function bvs_fkt(){
  //Hat der Benutzer die entsprechenden Berechtigungen?
  if(!current_user_can('manage_options')) {
    return;
  }
  ?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <p><?php echo iig_bvs_get_db_array_as_table_div(iig_bvs_get_all_data_div()); ?></p>
    <p><?php echo iig_bvs_get_db_array_as_table(iig_bvs_get_all_data()); ?></p>


  </div>
<?php }

/**
* Diese Funktion formatiert ein uebergebenes Array als Tabelle und gibt diese zurueck.
*
* @param array Es wird ein Array mit den Daten der Honorartabelle erwartet.
*
* @return string Es wird ein String zurueck gegeben.
*/
function iig_bvs_get_db_array_as_table($sql){
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_honorar";

  $output = "<table class='overview'><tr>
                      <th>Wert</th>
                      <th>Honorar</th>
                    </tr>";
  foreach ($sql as $value) {
    $the_post = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE ID = " . $value["id"] . ";");
    $output .= "<tr><td class='honorar'>" . (($the_post->optionen != '') ? (($the_post->optionen == '<=') ? 'bis ' : 'über ') : '') . iig_bvs_format_euro($the_post->wert) . "</td><td class='honorar'>" . iig_bvs_format_euro($the_post->honorar) . "</td></tr>";
  }
  $output .= "</table>";
  return $output;
}

/**
* Diese Funktion ueberprueft, ob ein Wert in der Honorartabelle unter Optionen abgelegt ist.
*
* @param string Es wird ein Character erwartet.
*
* @return boolean Es wird true (Optionen ist gesetzt) oder false (Optionen ist nicht gesetzt) zurueck gegeben.
*/
/*function iig_bvs_optionen_is_set($optionen) {
  if($optionen == '') {
    return false;
  } elseif ($optionen != '') {
    return true;
  }
}*/

/**
* Diese Funktion formatiert ein uebergebenes Array als Tabelle und gibt diese zurueck.
*
* @param array Es wird ein Array mit den Daten der Honorartabelle erwartet.
* @return string Es wird ein String zurueck gegeben.
*/
function iig_bvs_get_db_array_as_table_div($sql){
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_div";

  $output = "<table class='overview'><tr class='divTitle'>
                      <th>Bezeichnung</th>
                      <th>Wert</th>
                    </tr>";

  foreach ($sql as $value) {
    $the_post = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE ID = " . $value["id"] . ";");
    $output .= "<tr><td>" . $the_post->bezeichnung . "</td><td>" . iig_bvs_format_div($the_post->wert,$the_post->einstufung) . "</td></tr>";

  }
  $output .= "</table>";
  return $output;
}

?>
