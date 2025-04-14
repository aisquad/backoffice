<?php
namespace Backoffice\Views\Includes\Sidebar;

use Backoffice\Views\Includes\Sidebar\SidebarHeading;
use Backoffice\Views\Includes\Sidebar\SidebarSection;

class SidebarComponents 
{
    private $item_dict;
	private $active_item = '';

    public function __construct()
    {
        $url_array = explode("/", $_SERVER["REQUEST_URI"]);
		$location = str_replace('.php', '', end($url_array));
		if (count($url_array) == 3)
			$this->active_item = $location;
		elseif (count($url_array) == 4)
			$this->active_item = $url_array[2];	
		else	
			$this->active_item = 'dashboard';

		// echo "<div style='z-index: 9999;'><pre>";
		// print_r($url_array);
		// echo "url_end: $location\n";
		// echo "active: {$this->active_item}";
		// echo "</pre></div>";

		$this->populate();
    }

	private function populate() {
		$this->item_dict = [
			'dashboard' => new SidebarSection('Dashboard', 'grid', [], 'home'),
			'components' => new SidebarSection('Components', 'menu-button-wide', [
				'alerts', 'accordion', 'badges',
				'breadcrumbs', 'buttons', 'cards', 
				'carousel', 'datepickers', 'modal', 'tabs',
				'pagination', 'progress', 'spinners',
				'tooltips' 
			]),
			'forms' => new SidebarSection('Forms', 'journal-text', ['editors', 'elements', 'layouts', 'validation']),
			'tables' => new SidebarSection('Tables', 'layout-text-window-reverse', ['data', 'general']),
			'charts' => new SidebarSection('Charts', 'bar-chart', ['apexcharts', 'chartjs', 'echarts']),
			'datepickers' => new SidebarSection('Datepickers', 'calendar', ['journeys']), 
			'assests' => new SidebarSection('Assets', 'gem', ['bootstrap', 'boxicons', 'remix']),
			'pages' => new SidebarHeading('Pages'),
			'profile' => new SidebarSection('Profile', 'person', [], 'profile'),
			'faq' => new SidebarSection('F.A.Q.', 'question-circle', [], 'faq'),
			'contact' => new SidebarSection('Contact', 'envelope', [], 'contact'),
			'register' => new SidebarSection('Register', 'card-list', [], 'register'),
			'login' => new SidebarSection('Login', 'box-arrow-in-right', [], 'login'),
			'error' => new SidebarSection('Error 404', 'dash-circle', [], 'error-404'),
			'blank' => new SidebarSection('Blank', 'dash-circle', [], 'blank')
		];
	}

	public function build() {
		$rtn = '';
		foreach($this->item_dict as $section) {
			if ($section instanceof SidebarSection) {
				$rtn .= $this->buildSection($section);
			} else {
				$rtn .= $this->buildHeading($section);
			}
		}
		echo $rtn;
	}

	private function buildHeading($section) {
		return "$section";
	}

	private function buildSection($section) {
		$rtn = '';
		$name = $section->getName();
		$lwr_name = $section->getLowerName();
		if ($this->active_item == $lwr_name) {
			$section->setActive();
		}
		$collapsed = $section->isActive() ? '' :  ' collapsed';
		$href = $section->getHref();
		$items = $section->getItems();
		$icon = $section->getIcon();
		$show =  $section->isActive() ? ' show' : '';
		$rtn .= "      <li class=\"nav-item\">\n";

		if (empty($items)) {
			// echo "<div z-index=\"1060\">$href</div>";
			$href = str_replace('.php', '', $href);
			$rtn .= "        <a class=\"nav-link$collapsed\" href=\"/$href\">
          <i class=\"bi bi-$icon\"></i>
          <span>$name</span>
        </a>\n";

		} else {

			$rtn .= "        <a class=\"nav-link$collapsed\" data-bs-target=\"#$lwr_name-nav\" data-bs-toggle=\"collapse\" href=\"/$href\">
          <i class=\"bi bi-$icon\"></i><span>$name</span><i class=\"bi bi-chevron-down ms-auto\"></i>
        </a>\n";
			$rtn .= "        <ul id=\"$lwr_name-nav\" class=\"nav-content collapse$show\" data-bs-parent=\"#sidebar-nav\">\n";
			foreach($items as $item) {
				$rtn .= "$item\n";
			}
			$rtn .= '        </ul>';
		}
		$rtn .= "      </li><!-- End $name Nav -->";
		return $rtn;
	}

}
