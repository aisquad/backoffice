<?php
namespace Backoffice\Views\Includes\Sidebar;

class SidebarItem {
    private $name = '';
    private $title = '';
    private $section = '';
    private $folder = '';

    public function __construct($section, $folder, $name)
    {
        $this->section = $section;
        $this->folder = $folder;
        $this->name = $name;
        $this->title = ucwords(strtolower($this->name));        
    }

    public function __toString() : string {
        return "          <li>
            <a href=\"{$this->folder}/{$this->name}\">
              <i class=\"bi bi-circle\"></i><span>{$this->title}</span>
            </a>
          </li>";
    }

}