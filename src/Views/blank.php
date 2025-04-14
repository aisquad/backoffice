<?php
namespace Backoffice\Views;

require_once __DIR__ . '/../../vendor/autoload.php';

use Backoffice\Core\Auth;

$auth = new Auth();
$auth->check('*');
  
require_once __DIR__ . "/Includes/head.php";
require_once __DIR__ . "/Includes/header.php";
require_once __DIR__ . "/Includes/sidebar.php";

?>
  <main id="main" class="main">
    
    <?php require_once __DIR__ . "/Includes/page-title.php"; ?>
    
    <section class="section">
      <div class="row">
        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Example Card</h5>
              <p>This is an examle page with no contrnt. You can use it as a starter for your custom pages.</p>
            </div>
          </div>

        </div>

        <div class="col-lg-6">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Example Card</h5>
              <p>This is an examle page with no contrnt. You can use it as a starter for your custom pages.</p>
            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <?php require_once __DIR__ . "/Includes/footer.php"; ?>
  <?php require_once __DIR__ . "/Includes/footer-logout.html"; ?>

</body>

</html>