<?php

/**
* Diese Funktion implementiert eine CSS-Datei.
*/
function child_styles() {
	wp_enqueue_style( 'my-child-theme-style', get_stylesheet_directory_uri() . '/style.css', array('front-all'), false, 'all' );
}
add_action('wp_enqueue_scripts', 'child_styles', 11);

/**
* Diese Funktion wird per Shortcode aufgerufen und gibt eine Tabelle fuer den Leistungskatalog aus.
*
* @param array Der Funktion kann ein Array übergeben werden.
*
* @return string Es wird ein String zurueck gegeben.
*/
function leistungskatalog_func($atts) {
	$return = '<table><tbody><tr><td><div class="push " style="height: 10px;"></div>';
	$return .= do_shortcode('[icon name="theme-book-open" style="" color="" size="42" ]') . '</td>';
  $return .= '<td><h3>LEISTUNGSKATALOG</h3><p>Hier können Sie sich unseren aktuellen Leistungskatalog zur weiteren Übersicht herunterladen.</p></td>';
	$return .= '<td><div class="push " style="height: 30px;"></div>';
	$return .= '<p class="textcenter"><a class="vamtam-button accent1 button-border hover-accent1 " style="font-size: 14px;" href="/wp-content/uploads/2018/05/05_18-leistungskatalog.pdf" target="_blank"><span class="btext" data-text="DOWNLOAD">DOWNLOAD</span></a></p>';
	$return .= '</td></tr></tbody></table>';
	return $return;
}
add_shortcode('leistungskatalog','leistungskatalog_func');

/**
* Diese Funktion wird per Shortcode aufgerufen und gibt eine Tabelle fuer den Kontakt aus.
*
* @param array Der Funktion kann ein Array übergeben werden.
*
* @return string Es wird ein String zurueck gegeben.
*/
function kontaktAnfrage_func($atts) {
	$return = '<table><tbody><tr><td><div class="push " style="height: 10px;"></div>';
	$return .= do_shortcode('[icon name="theme-envelope-letter" style="" color="" size="42" ]') . '</td>';
	$return .= '<td><h3>KONTAKT / ANFRAGE</h3><p>Sie wünschen eine individuelle Beratung? Wir freuen uns über Ihr Interesse an unserem Unternehmen.</p></td>';
	$return .= '<td><div class="push " style="height: 30px;"></div>';
	$return .= '<p class="textcenter"><a class="vamtam-button accent1 button-border hover-accent1 " style="font-size: 14px;" href="/index.php/kontakt/" target="_self"><span class="btext" data-text="KONTAKT">KONTAKT</span></a></p>';
	$return .= '</td></tr></tbody></table>';
	return $return;
}
add_shortcode('kontaktAnfrage','kontaktAnfrage_func');

/**
* Diese Funktion wird per Shortcode aufgerufen und gibt eine Tabelle fuer den Kontaktdaten für Berlin aus.
*
* @param array Der Funktion kann ein Array übergeben werden.
*
* @return string Es wird ein String zurueck gegeben.
*/
function adresseBerlin_func($atts) {
	$return = '<table><tbody><tr>';
	$return .= '<td><div class="push " style="height: 10px;"></div>' . do_shortcode('[icon name="theme-map" style="" color="accent1" size="42" ] ') . '</td>';
	$return .= '<td>Standort Berlin<br />Kurfürstendamm 106<br />10711 Berlin-Halensee</td>';
	$return .= '</tr></tbody></table>';
	return $return;
}
add_shortcode('adresseBerlin','adresseBerlin_func');

/**
* Diese Funktion wird per Shortcode aufgerufen und gibt eine Tabelle fuer den Kontaktdaten für Leipzig aus.
*
* @param array Der Funktion kann ein Array übergeben werden.
*
* @return string Es wird ein String zurueck gegeben.
*/
function adresseLeipzig_func($atts) {
	$return = '<table><tbody><tr>';
	$return .= '<td><div class="push " style="height: 10px;"></div>' . do_shortcode('[icon name="theme-map" style="" color="accent1" size="42" ] ') . '</td>';
	$return .= '<td>Standort Leipzig<br />Brühl 62<br />04109 Leipzig Zentrum</td>';
	$return .= '</tr></tbody></table>';
	return $return;
}
add_shortcode('adresseLeipzig','adresseLeipzig_func');

/**
* Diese Funktion wird per Shortcode aufgerufen und gibt eine Trennlinie aus.
*
* @param array Der Funktion kann ein Array übergeben werden.
*
* @return string Es wird ein String zurueck gegeben.
*/
function trennlinieTitel_func($atts) {
	$return = '<p class="trennlinieTitel">&nbsp;</p>';
	return $return;
}
add_shortcode('trennlinieTitel','trennlinieTitel_func');

/* Unterbindet das Auslesen der WordPress-Version */
remove_action('wp_head','wp_generator');
