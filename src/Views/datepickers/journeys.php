<?php
namespace Backoffice\Views\Datepickers;

use Backoffice\Core\Auth;

$auth = new Auth();
$auth->check('*');
  
require_once __DIR__ . "/../Includes/head.php";
require_once __DIR__ . "/../Includes/header.php";
require_once __DIR__ . "/../Includes/sidebar.php";
?>

    <!-- Scripts JavaScript nécessaires -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.fr.min.js"></script>

  <main id="main" class="main">
    
    <?php require_once __DIR__ . "/../Includes/page-title.php"; ?>
    
    <section class="section">
      <div class="row align-items-top">
        <div class="col-lg-6">
        <div class="container mt-5">
        <h5>Sélectionnez une date</h5>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date">Date :</label>
                    <input type="text" class="form-control datepicker" id="date" placeholder="Choisissez une date">
                </div>
            </div>
        </div>


        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                language: 'fr',
                autoclose: true
            });
        });
    </script>
    </section>

    </main><!-- End #main -->

    <?php require_once __DIR__ . "/../Includes/footer.php"; ?>
    <?php require_once __DIR__ . "/../Includes/footer.php"; ?>

</body>

</html>