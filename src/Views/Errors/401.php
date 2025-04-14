<?php
namespace Backoffice\Views\Errors;

session_start();

function user_is_authenticated() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

if (!user_is_authenticated()) {
    header('WWW-Authenticate: Digest realm="Mon Application", qop="auth", nonce="'.uniqid().'"');
    header('HTTP/1.0 401 Unauthorized');
    header('Location: /errors/authentication-required');
    exit();
}

header('HTTP/1.0 401 Unauthorized');
require_once __DIR__ . "/../Includes/head.php";
?>
<body>

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>401</h1>
        <h2>Unauthorized.</h2>
        <a class="btn" href="/">Back to home</a>
        <img src="/assets/img/not-found.svg" class="img-fluid py-5" alt="Unauthorized">
        <div class="credits">
          Designed by Copyl&#x259;ft
        </div>
      </section>

    </div>
  </main><!-- End #main -->


  <?php require_once __DIR__ . "/../Includes/footer-min.php"; ?>

</body>

</html>