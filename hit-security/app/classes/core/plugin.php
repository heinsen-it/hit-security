<?php
namespace HITSecurity\App\Classes\Core;

/**
 * Hauptklasse für das HIT-Security Plugin
 */
class Plugin {
    /**
     * Eine Instanz dieser Klasse
     *
     * @var Plugin
     */
    private static $instance = null;

    /**
     * Plugin-Version
     *
     * @var string
     */
    private $version = '0.0.1.0';

    /**
     * Plugin-Name
     *
     * @var string
     */
    private $plugin_name = 'hit-security';



    /**
     * Konfigurations-Manager
     *
     * @var Config
     */
    private $config = null;

    /**
     * Logger-Instanz
     *
     * @var Logger
     */
    private $logger = null;

    /**
     * Container für Service-Klassen
     *
     * @var array
     */
    private $services = [];

    /**
     * Konstruktor
     */
    private function __construct() {
        $this->init_config();
        $this->init_logger();
        $this->include_files();
        $this->register_services();
        $this->init_hooks();
    }



    /**
     * Singleton-Pattern: Verhindert, dass mehr als eine Instanz dieser Klasse erstellt wird
     *
     * @return Plugin
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Bindet notwendige Dateien ein
     */
    private function include_files() {
        if(file_exists(HITSECURITY_PLUGIN_DIR.'lib/plugin-update-checker/plugin-update-checker.php')) {
            require HITSECURITY_PLUGIN_DIR . 'lib/plugin-update-checker/plugin-update-checker.php';
        }
    }

    /**
     * Initialisiert WordPress Hooks
     */
    private function init_hooks() {
        // Aktivierungs- und Deaktivierungshooks
        register_activation_hook(HITSECURITY_PLUGIN_BASENAME, array($this, 'activate'));
        register_deactivation_hook(HITSECURITY_PLUGIN_BASENAME, array($this, 'deactivate'));

        // Controller initialisieren
        add_action('init', array($this, 'init_controllers'));

        // Weitere Hooks
        add_action('init', array($this, 'load_textdomain'));
    }

    /**
     * Controller initialisieren
     */
    public function init_controllers() {
        // Beispiel für die Initialisierung eines Controllers
        $admin_controller = new \HITSecurity\App\Classes\Controller\AdminController();
        $admin_controller->init();

        // Beispiel für die Initialisierung eines Frontend-Controllers
        $frontend_controller = new \HITSecurity\App\Classes\Controller\FrontendController();
        $frontend_controller->init();
    }

    /**
     * Wird beim Aktivieren des Plugins ausgeführt
     */
    public function activate() {
        // Aktivierungslogik hier
        flush_rewrite_rules();
    }

    /**
     * Wird beim Deaktivieren des Plugins ausgeführt
     */
    public function deactivate() {
        // Deaktivierungslogik hier
        flush_rewrite_rules();
    }

    /**
     * Lädt die Textdomäne für Internationalisierung
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'testcase',
            false,
            dirname(HITSECURITY_PLUGIN_BASENAME) . '/languages'
        );
    }


    /**
     * Überprüft auf Updates und führt Datenbank-Migrationen aus
     */
    public function check_for_updates() {
        $options = get_option('hitsecurity_options', array());

        // Wenn keine Version gesetzt ist oder die Version niedriger ist als die aktuelle
        if (!isset($options['version']) || version_compare($options['version'], $this->version, '<')) {
            $migration = new \HITSecurity\App\Classes\Core\Migration($options['version'], $this->version);
            $migration->run();

            // Version aktualisieren
            $options['version'] = $this->version;
            update_option('hitsecurity_options', $options);

            // Migrationsinfo in die Log-Datei schreiben
            $this->logger->info('Plugin aktualisiert von Version ' . $options['version'] . ' auf ' . $this->version);
        }
    }


    /**
     * Wird beim Deinstallieren des Plugins ausgeführt
     */
    public static function uninstall() {
        // Plugin-Daten vollständig entfernen

        // Datenbanktabellen löschen
        $plugin = self::get_instance();
        $plugin->services['db']->drop_tables();

        // Optionen löschen
        delete_option('hitsecurity_options');

        // Cron-Jobs entfernen
        wp_clear_scheduled_hook('hitsecurity_daily_event');
    }

    /**
     * Setzt Standard-Optionen für das Plugin
     */
    private function set_default_options() {
        $options = get_option('hitsecurity_options', array());

        $defaults = array(
            'version' => $this->version,
            'enabled_features' => array('feature1', 'feature2'),
            'api_key' => '',
            'debug_mode' => false
        );

        // Neue Optionen mit bestehenden zusammenführen
        $options = wp_parse_args($options, $defaults);

        // Optionen aktualisieren
        update_option('hitsecurity_options', $options);
    }

}