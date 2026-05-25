<?php

/**
 * Plugin Name: HIT-Security
 * Plugin URI: https://heinsen-it.de/tools/plugins-wordpress/
 * Description: Plugin für verbesserte Sicherheit in WordPress
 * Version: 0.0.1.0
 * Author: Heinsen-IT
 * Author URI: https://heinsen-it.de
 * License: GPL2
 * Letztes Update: 2025-04-15 20:00:00
 * MINIMAL WP: 6.4.0
 * MINIMAL PHP: 8.2.0
 * Tested WP: 6.7.1
 */

// Direktzugriff verhindern
if (!defined('ABSPATH')) {
    exit;
}

// Autoloader für Namespaces definieren
spl_autoload_register(function ($class) {
    // Basis-Namespace für das Plugin
    $namespace = 'HITSecurity\\';

    // Prüfen, ob die angeforderte Klasse zu unserem Namespace gehört
    if (strpos($class, $namespace) !== 0) {
        return;
    }

    // Entferne den Plugin-Namespace
    $class = str_replace($namespace, '', $class);

    // Konvertiere Namespace-Trenner in Verzeichnis-Trenner
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    // Pfad zur Datei erstellen
    $file = plugin_dir_path(__FILE__) .  strtolower($class) . '.php';

    // Datei laden, wenn sie existiert
    if (file_exists($file)) {
        require_once $file;
    }
});

// Plugin-Konstanten definieren
define('HITSECURITY_VERSION', '0.0.1.0');
define('HITSECURITY_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HITSECURITY_PLUGIN_URL', plugin_dir_url(__FILE__));
define('HITSECURITY_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Plugin initialisieren
function hitsecurity_init()
{
    // Hauptklasse aus dem Core-Namespace laden und initialisieren
    return \HITSecurity\App\Classes\Core\Plugin::get_instance();
}

// Das Plugin starten
hitsecurity_init();
