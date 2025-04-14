<?php
namespace Backoffice\Views\Includes\Sidebar;

class SidebarHeading {
    private $name = '';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return "\n      <li class=\"nav-heading\">$this->name</li>\n";
    }
}