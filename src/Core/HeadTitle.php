<?php
namespace Backoffice\Core;

use Backoffice\Core\Config;
use Backoffice\Core\Sql\DataLoader;

class HeadTitle {
    private string $url = '';
    private string $title = '';
    private ?string $description = '';
    private ?string $keywords = '';
    private Config $config;

    public function __construct()
    {
        $this->config = Config::getInstance();
        $sqlLoader = new DataLoader();
        $urlArray = explode("/", $_SERVER["REQUEST_URI"]);
        $location = str_replace('.php', '', end($urlArray));
        $result = $sqlLoader->fetch("SELECT * FROM pages WHERE url = ?;", [$location]);
        if($result) {
            $page = $result[0];
            $this->url = $location;
            $this->title = $page['title'];
            $this->description = $page['description'];
            $this->keywords = $page['keywords'];
        } else {
            $this->url = $location;
            $words = explode('-', $location, 2);
            if (count($words)>1) {
                $this->title = ucfirst(strtolower($words[0])) . " - " . ucfirst(strtolower($words[1]));
            } else {
                $this->title = ucfirst(strtolower($words[0]));
            }
            $this->description = '';
            $this->keywords = '';
            
        }           
    }

    public function getUrl() {
        return $this->url;
    }

    public function getTitle() {
        return "{$this->config->owner} · {$this->title}";
    }

    public function getDescription() {
        return $this->description;
    }

    public function getKeywords() {
        return $this->keywords;
    }
}
