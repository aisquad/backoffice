<?php
namespace Backoffice\Views\Includes;

use Backoffice\Core\HeadTitle;

$page = new HeadTitle();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Backoffice - <?php echo $page->getTitle();?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="/assets/img/favicon.png" rel="icon">
  <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/assets/js/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/js/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/js/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/assets/js/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="/assets/js/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="/assets/js/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/assets/js/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="/assets/css/style.css" rel="stylesheet">

</head>

