<?php
namespace Backoffice\Core;

use Backoffice\Core\Config;

class Logger {
    /**
     * Deployment:
     * - add folders /logs/api/, /logs/sql/
     */
    private Config $config;
    private string $subject = 'errors';
    private string $subfolder = '';
    private string $displayErrors = '0';
    public const LOG_INFO = 'info';
    public const LOG_ERROR = 'error';
    public const LOG_WARNING = 'warning';
    public const LOG_DEBUG = 'debug';

    public function __construct(string $subject='errors', $displayErrors = '1'){
        $this->config = Config::getInstance();
        $this->subject = $subject;
        $this->displayErrors = $displayErrors;
        $this->dispatch();
    }

    private function checkLogDirectory() {
        $dir = dirname($this->getLogFilePath());
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
            throw new RuntimeException("Impossible de créer le répertoire de logs.");
        }
        if (!is_writable($dir)) {
            throw new RuntimeException("Le répertoire de logs n'est pas accessible en écriture.");
        }
    }

    private function dispatch() {
        $this->subfolder = match ($this->subject) {
            'api' => (function() {
                $urlArray = explode("/", $_SERVER["REQUEST_URI"]);
                $location = strtr(pathinfo(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), PATHINFO_FILENAME), '-', '_');
                $this->subject = $location;                       
                return 'api/';
            })(),
            'jwt' => 'api/',
            'php' => (function() {
                $this->subject = 'php_errors_';
                return '';
            })(),
            'sql' => 'sql/',
            default => ''
        };


        if ($this->config->env === 'dev') {
            $this->setDevLogging();
        } elseif ($this->config->env === 'prod') {
            $this->setProdLogging();
        }
    }

    private function setDevLogging() {
        // DEV
        $show_start_up_errs = $this->config->display_startup_errors ? '1' : '0';
        ini_set('display_startup_errors', $show_start_up_errs);
        ini_set('display_errors', $this->displayErrors);
        ini_set('log_errors', '1');
        ini_set('error_log', $this->getLogFilePath());
        error_reporting(E_ALL);
    }

    private function setProdLogging() {
        // PROD
        ini_set('display_errors', '0');
        ini_set('log_errors', '1');
        ini_set('error_log', $this->getLogFilePath());
    }

    private function getLogFilePath() {
        return __DIR__ . "/../../logs/{$this->subfolder}{$this->subject}_" . date('Ymd') . '.log';
    }

    public function log($message, $level = 'info') {
        $upperLevel = strtoupper($level);
        $logMessage = date('Y-m-d H:i:s') . " [$upperLevel] $message\n";
        error_log($logMessage, 3, $this->getLogFilePath());
    }

    public function info($message) {
        $this->log($message, self::LOG_INFO);
    }
    
    public function error($message) {
        $this->log($message, self::LOG_ERROR);
    }
    
    public function warning($message) {
        $this->log($message, self::LOG_WARNING);
    }
    
    public function debug($message) {
        $this->log($message, self::LOG_DEBUG);
    }

    public function logException(Throwable $e) {
        $this->log($e->getMessage() . "\n" . $e->getTraceAsString(), self::LOG_ERROR);
    }    
}