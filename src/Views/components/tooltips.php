<?php
namespace Backoffice\Views\Components;

use Backoffice\Core\Auth;

$auth = new Auth();
$auth->check('*');
  
require_once __DIR__ . "/../Includes/head.php";
require_once __DIR__ . "/../Includes/header.php";
require_once __DIR__ . "/../Includes/sidebar.php";
?>
  <main id="main" class="main">
    
    <?php require_once __DIR__ . "/../Includes/page-title.php"; ?>
    
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Tooltips Examples</h5>
              <p>Hover over the buttons below to see the four tooltips directions: top, right, bottom, and left. </p>

              <!-- Tooltips Examples -->
              <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">
                Tooltip on top
              </button>
              <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="right" title="Tooltip on right">
                Tooltip on right
              </button>
              <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tooltip on bottom">
                Tooltip on bottom
              </button>
              <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="left" title="Tooltip on left">
                Tooltip on left
              </button>
              <!-- End Tooltips Examples -->

            </div>
          </div>

        </div>

      </div>
    </section>

  </main><!-- End #main -->

  <?php require_once __DIR__ . "/../Includes/footer.php"; ?>
  <?php require_once __DIR__ . "/../Includes/footer.php"; ?>

</body>

</html>