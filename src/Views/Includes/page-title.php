<?php
namespace Backoffice\Views\Includes;

$url_array = explode("/", $_SERVER["REQUEST_URI"]);
$section_title = array_slice($url_array, 2);
$section = ucwords(strtolower(str_replace('.php', '', reset($section_title))));
$title = ucwords(strtolower(str_replace('.php', '', end($section_title))));
// echo "<div z-index=\"9999\"><pre>";
// print_r($url_array);
// print_r($section_title);
// echo "</pre></div>";
if ($title == "Faq") {
  $title = "F.A.Q.";
  $section = "F.A.Q.";
}
?>

    <div class="pagetitle">
      <h1><?php echo $title; ?></h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <?php if ($section != $title) echo "<li class=\"breadcrumb-item\">$section</li>"; ?>
          <li class="breadcrumb-item active"><?php echo $title; ?></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->