<?php 
namespace Backoffice\Views\Errors;

header("HTTP/1.0 404 Not Found");
require_once __DIR__ . "/../Includes/head.php";
?>
<body>

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>404</h1>
        <h2>The page you are looking for doesn't exist.</h2>
        <a class="btn" href="/">Back to home</a>
        <img src="/assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">
        <div class="credits">
          Designed by Copyl&#x259;ft
        </div>
      </section>

    </div>
  </main><!-- End #main -->


  <?php require_once __DIR__ . "/../Includes/footer-min.php"; ?>

</body>

</html>