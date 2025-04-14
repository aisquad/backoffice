<?php 
namespace Backoffice\Views\Errors;

header("HTTP/1.1 401 Unauthorized");
require_once __DIR__ . "/../Includes/head.php";
?>
<body>

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>401</h1>
        <h2>Unauthorized. Authentication required.</h2>
        <a class="btn" href="/">Back to home</a>
        <img src="/assets/img/not-found.svg" class="img-fluid py-5" alt="Unauthorized">
        <div class="credits">
          Designed by Copyl&#x259;ft
        </div>
      </section>

    </div>
  </main><!-- End #main -->


  <?php require_once __DIR__ . "/includes/footer-min.php"; ?>

</body>

</html>