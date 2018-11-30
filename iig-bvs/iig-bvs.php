<?php

/*
Plugin Name:  IIG - BVS Verkehrswertgutachten
Description:  Kosten für das Verkehrswertgutachten gem. BVS Richtlinie ermitteln.
Author:       Andreas Roos
Version:      1.0
License:      GPL v2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.txt
*/

// Diese Datei wird nicht ausgefuehrt, wenn sie direkt aufgerufen wird.
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

if (is_admin()) {
  require_once plugin_dir_path( __FILE__ ) . 'admin/admin-menu.php';

  require_once plugin_dir_path( __FILE__ ) . 'admin/bvs-overview.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/bvs-edit.php';
  require_once plugin_dir_path( __FILE__ ) . 'admin/bvs-help.php';
}

require_once plugin_dir_path( __FILE__ ) . 'includes/bvs-db-all.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/bvs-format.php';

require_once plugin_dir_path( __FILE__ ) . 'public/bvs-output.php';

/**
* Diese Funktion legt die notwendigen Tabllen fuer dieses Plugin an.
*/
function iig_bvs_setup_database() {
  global $wpdb;

  $table_name = $wpdb->prefix . "bvs_honorar";
  $table_name_div = $wpdb->prefix . "bvs_div";

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){

    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      wert int NOT NULL,
      honorar int NOT NULL,
      optionen char(2) DEFAULT '' NOT NULL,
      PRIMARY KEY  (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    $wpdb->query($sql);
  }

  if($wpdb->get_var("SHOW TABLES LIKE '$table_name_div'") != $table_name_div){

    $sql = "CREATE TABLE $table_name_div (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      bezeichnung varchar(255) NOT NULL,
      wert float(5,2) NOT NULL,
      kategorie varchar(255) NOT NULL,
      einstufung char(1) NOT NULL,
      PRIMARY KEY  (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    $wpdb->query($sql);
  }
}

// Setup des Plugins - Datenbank mit Inhalten fuellen
/**
* Diese Funktion fuegt Inhalte in den entsprechenden Tabellen ein.
*/
function iig_bvs_setup_database_data() {
  global $wpdb;

	$table_name = $wpdb->prefix . 'bvs_honorar';
  $table_name_div = $wpdb->prefix . "bvs_div";

  //array(int $wert,$int honorar,char $optionen)
  $wert_honorar = array(
    array(150000,1500,'<='),
    array(200000,1600,''),
    array(250000,1700,''),
    array(300000,1800,''),
    array(350000,1900,''),
    array(400000,2000,''),
    array(450000,2100,''),
    array(500000,2200,''),
    array(750000,2500,''),
    array(1000000,2800,''),

    array(1250000,3100,''),
    array(1500000,3400,''),
    array(1750000,3700,''),
    array(2000000,4000,''),
    array(2250000,4300,''),
    array(2500000,4600,''),
    array(3000000,5000,''),
    array(3500000,5400,''),
    array(4000000,5700,''),
    array(4500000,6100,''),

    array(5000000,6500,''),
    array(7500000,8400,''),
    array(10000000,10100,''),
    array(12500000,11800,''),
    array(15000000,13500,''),
    array(17500000,15200,''),
    array(20000000,16900,''),
    array(22500000,18600,''),
    array(25000000,20300,''),
    array(25000000,22000,'>')
  );

  for($i=0; $i<count($wert_honorar); $i++) {
      $wpdb->insert($table_name,array('wert' => $wert_honorar[$i][0],'honorar' => $wert_honorar[$i][1], 'optionen' => $wert_honorar[$i][2]));
  }
  /**
  * array(String $Bezeichnung, float $Wert, String $Kategorie, Char $Einstufung)
  *
  * e = Euro
  * f = Faktor
  * p = Prozent
  *
  * Kategorie = Die Basis fuer nach dem BVS-Katalog benoetigte Berechnungen.
  */
  $bvs_div = array(
    array('Wertermittlungsstichtag',20.,'Stichtag','p'),
    array('Qualitätsstichtag',20.,'Stichtag','p'),
    array('Erbbaurecht',40.,'Recht','p'),
    array('Wegerecht',20.,'Recht','p'),
    array('Leitungsrecht',20.,'Recht','p'),
    array('Wohnungsrecht',30.,'Recht','p'),
    array('Nießbrauchsrecht',30.,'Recht','p'),
    array('Überbau',30.,'Recht','p'),
    array('Korrekturfaktor',0.5,'Recht','f'),
    array('Gutachtenaktualisierung',0.6,'Recht','f'),
    array('Erschwerte Bedingungen',1.2,'Zuschlag','f'),
    array('Erschwerte Bedingungen MIN',200.,'Zuschlag','e'),
    array('Besondere Leistungen',20.,'Zuschlag','p'),
    array('Nebenkosten',4.,'Weitere','p'),
    array('Umsatzsteuer',19.,'Weitere','p'),
  );

  for($i=0; $i<count($bvs_div); $i++) {
    $wpdb->insert($table_name_div,array('bezeichnung' => $bvs_div[$i][0],'wert' => $bvs_div[$i][1],'kategorie' => $bvs_div[$i][2],'einstufung' => $bvs_div[$i][3]));
  }
}

/**
* Diese Funktion entfernt die bei der Aktivierung des Plugins angelegten Tabllen.
*/
function iig_bvs_del_database(){
  global $wpdb;

	$table_name = $wpdb->prefix . 'bvs_honorar';
  $table_name_div = $wpdb->prefix . "bvs_div";

  $sql_honorar = "DROP TABLE IF EXISTS $table_name";
  $sql_div = "DROP TABLE IF EXISTS $table_name_div";

  $wpdb->query($sql_honorar);
  $wpdb->query($sql_div);
}

// Aktivierungs-Hooks
register_activation_hook( __FILE__, 'iig_bvs_setup_database' );
register_activation_hook( __FILE__, 'iig_bvs_setup_database_data' );

// Deaktivierungs-Hooks
register_deactivation_hook( __FILE__, 'iig_bvs_del_database' );
