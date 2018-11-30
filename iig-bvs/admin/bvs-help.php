<?php

// Diese Datei wird nicht ausgefuehrt, wenn sie direkt aufgerufen wird.
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

/**
* Diese Funktion stellt eine eigene CSS-Datei bereit.
*/
function iig_bvs_css_help_script() {
   $url = plugin_dir_url( __FILE__ ) . 'css/admin.css';
   wp_register_style( 'help-styles',  $url);
   wp_enqueue_style( 'help-styles' );
}
add_action('admin_enqueue_scripts', 'iig_bvs_css_help_script');

/**
* Diese Funktion wird direkt wird direkt aus dem Adminmenue aufgerufen.
*/
function bvs_help_fkt(){
  //Hat der Benutzer die entsprechenden Berechtigungen?
  if(!current_user_can('manage_options')) {
    return;
  }
  ?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <p>Auf dieser Seite finden Sie einige Informationen bzgl. dem Umgang des Plugins.</p>

    <p>Die aufgeführten Shortcodes werden unter dem Menüpunkt Seite verwendet.</p>

    <table class="faq">
      <tr>
        <th class="faqTablePadding faqTableBorder">Shortcode</th>
        <th class="faqTableBorder">Beschreibung</th>
      </tr>


      <tr>
        <td class="faqTablePadding faqTableBorder">[adresseBerlin]</td>
        <td class="faqTableBorder">Gibt die Standort-Daten von Berlin als Tabelle zurück.</td>
      </tr>
      <tr>
        <td class="faqTablePadding faqTableBorder">[adresseLeipzig]</td>
        <td class="faqTableBorder">Gibt die Standort-Daten von Leipzig als Tabelle zurück.</td>
      </tr>
      <tr>
        <td class="faqTablePadding faqTableBorder">[bvs]</td>
        <td class="faqTableBorder">Gibt ein Dropdown Menü mit den Werten der Honorartabelle aus.</td>
      </tr>
      <tr>
        <td class="faqTablePadding faqTableBorder">[bvs-ausgabe]</td>
        <td class="faqTableBorder">Anhand der Auswahl des Dropdown Menüs wird das Honorar zzgl. Nebenkosten und MwSt. errechnet und ausgegeben.</td>
      </tr>
      <tr>
        <td class="faqTablePadding faqTableBorder">[kontaktAnfrage]</td>
        <td class="faqTableBorder">Eine Verlinkung mit Beschreibung wird als Ansicht einer Kontakt-Info ausgegeben.</td>
      </tr>
      <tr>
        <td class="faqTablePadding faqTableBorder">[leistungskatalog]</td>
        <td class="faqTableBorder">Eine Verlinkung mit Beschreibung wird als Ansicht einer Kontakt-Info ausgegeben.</td>
      </tr>
      <tr>
        <td class="faqTablePadding faqTableBorder">[trennlinieTitel]</td>
        <td class="faqTableBorder">Es wird ein graues Rechteck dargestellt.</td>
      </tr>
    </table>

  </div>
<?php }

?>
