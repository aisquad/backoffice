<?php 
  namespace Backoffice\Views\Includes;

  use Backoffice\Views\Includes\Sidebar\SidebarComponents;
?>

<!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <?php 
        $sidebar = new SidebarComponents();
        $sidebar->build();
      ?>
    </ul>

  </aside><!-- End Sidebar-->
