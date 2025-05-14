<?php

class securitytxt{


    /**
     * Konfiguration für security.txt
     *
     * @var array
     */
    private $config = [];

    private $logger;

    /**
     * Konstruktor
     *
     * @param array $config Konfigurationsdaten für security.txt
     * @param Logger|null $logger Logger-Instanz (optional)
     */
    public function __construct(array $config = [], $logger = null) {
        $this->logger = $logger;

        // Standard-Konfiguration
        $this->config = [
            'contacts' => [],
            'expires' => null,
            'encryption' => null,
            'acknowledgments' => null,
            'policy' => null,
            'hiring' => null,
            'preferred_languages' => null,
            'canonical' => null,
        ];

        // Benutzerdefinierte Konfiguration anwenden
        if (!empty($config)) {
            // TODO : Create update_config
            $this->update_config($config);
        }
    }













    /**
     * Verschlüsselungsinformation setzen
     *
     * @param string $encryption URL zum Verschlüsselungsschlüssel
     * @return $this
     */
    public function set_encryption($encryption) {
        $this->config['encryption'] = $encryption;
        return $this;
    }

    /**
     * Anerkennungs-URL setzen
     *
     * @param string $acknowledgments URL zur Anerkennungsseite
     * @return $this
     */
    public function set_acknowledgments($acknowledgments) {
        $this->config['acknowledgments'] = $acknowledgments;
        return $this;
    }

    /**
     * Sicherheitsrichtlinien-URL setzen
     *
     * @param string $policy URL zur Sicherheitsrichtlinie
     * @return $this
     */
    public function set_policy($policy) {
        $this->config['policy'] = $policy;
        return $this;
    }








}
