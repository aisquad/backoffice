<?php
namespace Backoffice\Views\Includes\Sidebar;

use Backoffice\Views\Includes\Sidebar\SidebarItem;

class SidebarSection {
    private $name = '';
    private $lower_name = '';
    private $folder = '';
    private $icon = '';
    private $items = [];
    private $href = '';
    private $active = false;

    public function __construct(string $section_name, $icon, $items=[], $href='#') {
        $this->name = $section_name;
        $this->lower_name = strtolower(str_replace('.', '', $section_name));
        $this->folder = $section_name == 'Dashboard' ? '/' : "/{$this->lower_name}";
        // echo "<div z-index=\"1060\">section: $section_name href: $href folder: $this->folder</div>";
        $this->icon = $icon;
        $this->href = $href;
        $this->populate($items);
    }

    public function getName() {
        return $this->name;
    }

    public function getLowerName() {
        return $this->lower_name;
    }

    public function getHref() {
        if ($this->href === '#')
            return $this->href;
        return "{$this->href}.php";
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getItems() {
        $rtn = [];
        foreach($this->items as $item) {
            $rtn[] = "$item\n";
        }
        return $rtn;
    }

    public function getTitle() {
        return $this->href;
    }

    private function populate($items) {
        foreach($items as $item) {
            $item = new SidebarItem($this->lower_name, $this->folder, $item);
            $this->items[] = $item;
        }
    }

    public function setActive() {
        $this->active = true;
    }

    public function isActive() {
        return $this->active;
    }
}