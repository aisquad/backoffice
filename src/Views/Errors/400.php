<?php 
namespace Backoffice\Views\Errors;

header("HTTP/1.0 400 Bad Request");
require_once __DIR__ . "/../Includes/head.php";
?>
<body>

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>400</h1>
        <h2>Bad Request.</h2>
        <a class="btn" href="/">Back to home</a>
        <img src="/assets/img/not-found.svg" class="img-fluid py-5" alt="Bad Request">
        <div class="credits">
          Designed by Copyl&#x259;ft
        </div>
      </section>

    </div>
  </main><!-- End #main -->

  <?php require_once __DIR__ . "/../Includes/footer-min.php"; ?>

</body>

</html>