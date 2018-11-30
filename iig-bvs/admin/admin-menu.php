<?php

// Diese Datei wird nicht ausgefuehrt, wenn sie direkt aufgerufen wird.
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

/**
* Diese Funktion legt einen neuen Menüpunkt mit Unterpunkten im Adminbereich an.
*/
function iig_bvs_menu() {
  add_menu_page('BVS Verkehrswertgutachten', 'IIG - BVS', 'manage_options', 'iig_bvs', 'bvs_fkt', '', 3);

  add_submenu_page('iig_bvs','BVS - Bearbeiten', 'Bearbeiten', 'manage_options', 'iig_bvs_edit', 'bvs_edit_fkt', '', 4);
  add_submenu_page('iig_bvs','BVS - Hilfe', 'Hilfe', 'manage_options', 'iig_bvs_help', 'bvs_help_fkt', '', 5);
}

add_action('admin_menu','iig_bvs_menu');

 ?>
