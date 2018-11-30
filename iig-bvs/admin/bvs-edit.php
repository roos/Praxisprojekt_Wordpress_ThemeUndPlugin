<?php

// Diese Datei wird nicht ausgefuehrt, wenn sie direkt aufgerufen wird.
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

/**
* Diese Funktion stellt eine eigene CSS-Datei bereit.
*/
function iig_bvs_css_edit_script() {
   $url = plugin_dir_url( __FILE__ ) . 'css/admin.css';
   wp_register_style( 'edit-styles',  $url);
   wp_enqueue_style( 'edit-styles' );
}
add_action('admin_enqueue_scripts', 'iig_bvs_css_edit_script');

add_action( 'admin_footer', 'my_action_javascript' ); // Write our JS below here

/**
* Diese Funktion stellt JavaScript bereit und 'manipuiert' entsprechend die Seite.
*/
function my_action_javascript() { ?>
	<script type="text/javascript" >
	jQuery(document).on('click','#delbtn',function($){
		var data = {
			'action': 'my_action',
			'daten': $.currentTarget.name //Ermittlung und Zuweisung der id des entsprechenden div-Elementes (bsp:honorar_del_01)
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
      jQuery("#" + response).remove();
      document.getElementsByClassName("reihe")[0].innerHTML = "bis";
      var arr = document.getElementsByClassName("reihe");
      document.getElementsByClassName("reihe")[arr.length-1].innerHTML = "über";
		});
	});
	</script> <?php
}

add_action( 'wp_ajax_my_action', 'my_action' );

/**
* Dies ist die serverseitige Funktion, die per Ajax vom Client aufgerufen wird.
* Hier ist der Einstiegspunkt um einen Datensatz zu entfernen.
*/
function my_action() {
	global $wpdb; // this is how you get access to the database
  $daten = $_POST['daten'];

  if(iig_bvs_del_db_val('honorar', substr($daten, -2))) {
    echo $daten;
  } else {
    echo "false";
  }

	wp_die(); // this is required to terminate immediately and return a proper response
}

/**
* Diese Funktion wird direkt wird direkt aus dem Adminmenue aufgerufen.
*/
function bvs_edit_fkt(){
  //Hat der Benutzer die entsprechenden Berechtigungen?
  if(!current_user_can('manage_options')) {
    return;
  }

  ?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php
    $div = "div";
    $honorar = "honorar";
    if ( isset( $_POST['submit'] ) ){
      foreach($_POST as $key => $value){
        if((substr($key,0,3)==$div)){
          if((substr($key,4,1)=="b")){
            //echo "Bezeichnung: " . $value . " " . iig_bvs_change_db_val($div, (substr($key,6,2)), 'bezeichnung', $value);
            iig_bvs_change_db_val($div, (substr($key,6,2)), 'bezeichnung', $value);
          } elseif((substr($key,4,1)=="w")) {
            //echo "Wert: " . $value . " " . iig_bvs_change_db_val($div, (substr($key,6,2)), 'wert', $value) ."<br />";
            iig_bvs_change_db_val($div, (substr($key,6,2)), 'wert', $value);
          }
        } elseif((substr($key,0,7)==$honorar)) {
          if((substr($key,8,1)=="w")){
            //echo "Wert: " . $value . " " . iig_bvs_change_db_val($honorar, (substr($key,10,2)), 'wert', $value);
            iig_bvs_change_db_val($honorar, (substr($key,10,2)), 'wert', $value);
          } elseif((substr($key,8,1)=="h")) {
            //echo "Honorar: " . $value . " " . iig_bvs_change_db_val($honorar, (substr($key,10,2)), 'honorar', $value) . "<br />";
            iig_bvs_change_db_val($honorar, (substr($key,10,2)), 'honorar', $value);
          }
        }
      } //foreach
    } //if
    ?>

    <form method="post">
      <?php echo iig_bvs_get_db_array_as_input_div(iig_bvs_get_all_data_div()); ?>
      <br /><br />

      <?php echo iig_bvs_get_db_array_as_input(iig_bvs_get_all_data()); ?>
      <br /><br />
      <button type="reset">Reset</button>
      <button type="submit" name="submit">Speichern</button>

    </form>

  </div>

<?php }

/**
* Diese Funktion aktualisiert Datenbankeintraege.
*
* @param string $db_table: Es wird der Tabellenname erwartet.
* @param int $id: Es wird eine ID erwartet.
* @param string $key: Es wird der Spaltenname erwartet.
* @param string $val: Es wird der neue Wert erwartet.
*/
function iig_bvs_change_db_val($db_table, $id, $key, $val) {
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_" . $db_table;

  if(strcmp($val, iig_bvs_get_data($db_table,$id,$key)) != 0){
    $wpdb->update($table_name, array($key=>$val), array('id'=>$id));
  }
}

/**
* Diese Funktion entfernt einen Datensatz innerhalb der Datenbank.
*
* @param string $db_table: Es wird der Tabellenname erwartet.
* @param int $id: Es wird eine ID erwartet.
*
* @return bool Es wird bei erfolgreicher Loeschung true ansonsten false zurueck gegeben.
*/
function iig_bvs_del_db_val($db_table, $id) {
  /*
      !!!

      ALLE echo's werden mit an die id gehangen und es kommt bei der
      Manipulation des DOM zu einem Fehler.

      !!!
  */

  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_" . $db_table;

  $sql = $wpdb->get_row("SELECT optionen FROM " . $table_name . " WHERE id='" . $id . "';");

    if(strcasecmp($sql->optionen,'<=') == 0) {
      iig_bvs_set_new_min($table_name,$id);
    } elseif(strcasecmp($sql->optionen,'>') == 0) {
      iig_bvs_set_new_max($table_name,$id);
    }

  $result = ($wpdb->delete( $table_name, array( 'ID' => $id ) ) != false) ? 'true': 'false';

  return $result;
}

/**
* Diese Funktion formatiert ein uebergebenes Array als Tabelle und gibt diese zurueck.
*
* @param array Es wird ein Array mit den Daten der Honorartabelle erwartet.
*
* @return string Es wird ein String zurueck gegeben.
*/
function iig_bvs_get_db_array_as_input($sql){
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_honorar";

  $output = "";
  foreach ($sql as $value) {
    $the_post = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE ID = " . $value["id"] . ";");
    $id = str_pad(($the_post->id), 2, 0, STR_PAD_LEFT); // Zahlen werden mit vorgestellten Nullen aufgefuellt. (zweistellig)
    //$output .= "<p id='honorar_del_{$id}'><input class='textRight' type='text' name='honorar_w_{$id}' id='{$the_post->wert}' maxlength='30' value='" . (($the_post->optionen != '') ? $the_post->optionen : '') . " " . $the_post->wert . "'><input class='textRight' type='text' name='honorar_h_{$the_post->id}' id='{$the_post->honorar}' maxlength='30' value='{$the_post->honorar}'><input id='delbtn' type='button' name='honorar_del_{$id}' value='löschen'></p>";
    $output .= "<div id='honorar_del_{$id}'><p class='reihe'>" . (($the_post->optionen != '') ? (($the_post->optionen == '<=') ? 'bis ' : 'über ') : '') . "</p><input class='textRight' type='text' name='honorar_w_{$id}' id='{$the_post->wert}' value='{$the_post->wert}'><input class='textRight' type='text' name='honorar_h_{$the_post->id}' id='{$the_post->honorar}' value='{$the_post->honorar}'><input id='delbtn' type='button' name='honorar_del_{$id}' value='löschen'></div>";
  }

  //$output .= "<input name='btn-neu-honorar' type='button' value='Neuen Eintrag hinzufügen.'>";
  return $output;
}

/**
* Diese Funktion formatiert ein uebergebenes Array als Tabelle und gibt diese zurueck.
*
* @param array Es wird ein Array mit den Daten der Honorartabelle erwartet.
*
* @return string Es wird ein String zurueck gegeben.
*/
function iig_bvs_get_db_array_as_input_div($sql){
  global $wpdb;
  $table_name = $wpdb->prefix . "bvs_div";

  $output = "";
  foreach ($sql as $value) {
    $the_post = $wpdb->get_row("SELECT * FROM " . $table_name . " WHERE ID = " . $value["id"] . ";");
    $id = str_pad(($the_post->id), 2, 0, STR_PAD_LEFT);
    //$output .= "<p id='div_del_{$id}'><input type='text' name='div_b_{$id}' id='{$the_post->bezeichnung}' maxlength='50' value='{$the_post->bezeichnung}'><input type='text' name='div_w_{$the_post->id}' id='{$the_post->wert}' maxlength='50' value='{$the_post->wert}'><input id='delbtn' type='button' name='div_del_{$id}' value='löschen'></p>";
    $output .= "<input type='text' name='div_b_{$id}' id='{$the_post->bezeichnung}' maxlength='50' value='{$the_post->bezeichnung}'><input style='margin-right:20px' type='text' name='div_w_{$the_post->id}' id='{$the_post->wert}' maxlength='50' value='{$the_post->wert}'>";
  }

  return $output;
}

/**
* Diese Funktion markiert innerhalb der Tabelle den neuen kleinen Wert.
*
* @param string $table_name: Es wird eine Tabellenbezeichnung erwartet.
* @param int $id: Es wird eine ID erwartet.
*/
function iig_bvs_set_new_min($table_name,$id) {
  global $wpdb;
  $new_min = iig_bvs_get_min($table_name,$id);
  $wpdb->update($table_name, array('optionen'=>'<='), array('id'=>$new_min));
}

/**
* Diese Funktion markiert innerhalb der Tabelle den neuen groessten Wert.
*
* @param string $table_name: Es wird eine Tabellenbezeichnung erwartet.
* @param int $id: Es wird eine ID erwartet.
*/
function iig_bvs_set_new_max($table_name,$id) {
  global $wpdb;
  $new_max = iig_bvs_get_max($table_name,$id);
  $wpdb->update($table_name, array('optionen'=>'>'), array('id'=>$new_max));
}

/**
* Diese Funktion ermittelt den neuen kleinsten Wert.
*
* @param string $table_name: Es wird eine Tabellenbezeichnung erwartet.
* @param int $id: Es wird eine ID erwartet.
*
* @return int Es wird die ID des neuen kleinsten Wertes zurueck gegeben.
*/
function iig_bvs_get_min($table_name,$id) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT id FROM " . $table_name . " WHERE  wert=(SELECT MIN(wert) FROM " . $table_name . " WHERE NOT id = " . $id . ");", ARRAY_A);

  return $result['id'];
}

/**
* Diese Funktion ermittelt den neuen groessten Wert.
*
* @param string $table_name: Es wird eine Tabellenbezeichnung erwartet.
* @param int $id: Es wird eine ID erwartet.
*
* @return int Es wird die ID des neuen groessten Wertes zurueck gegeben.
*/
function iig_bvs_get_max($table_name,$id) {
  global $wpdb;
  $result = $wpdb->get_row("SELECT id FROM " . $table_name . " WHERE  wert=(SELECT MAX(wert) FROM " . $table_name . " WHERE NOT id = " . $id . ");", ARRAY_A);

  return $result['id'];
}

?>
