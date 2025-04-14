<?php
namespace Backoffice\Views;

require_once __DIR__ . '/../../vendor/autoload.php';


use Backoffice\Core\Auth;
use Backoffice\Core\Logger;
use Backoffice\Core\Jwt;
use Backoffice\Core\RsaHandler;
use Backoffice\Core\Sql\DataLoader;
use Backoffice\Core\Sql\SqlManager;
use Backoffice\Core\Utils\Base64Handler;
use Backoffice\Core\I18n;
use Backoffice\Utils\Base64Obfuscator;
    
$logger = new Logger();
$auth = new Auth();
$auth->check();
I18n::init();

?>
<!DOCTYPE html>
<html lang="es">

<?php 
    require_once __DIR__ . "/Includes/head.php"; 
?>
<body>

<?php
    require_once __DIR__ . "/Includes/header.php";
    require_once __DIR__ . "/Includes/sidebar.php";
?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="home.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <div z-index="99999"><?php 
            // echo "<pre>";
            $weird = new Base64Obfuscator();
            
            // // Creació header i payload
            // $header = new JWTHeader();
            // $payload = new PayLoad('http://Backoffice\.local', '1');

            // // Creació jwt
            // $jwt_handler = new LiteJWT($header, $payload); 
            // $jwt = $jwt_handler->encode();

            // if ($jwt_handler->verify($jwt)) {
            //   echo "Signature valide!\n";
            // } else {
            //   echo "Signature invalide ou clé incorrecte. c: $computed_signature s: $received_signature_bytes\n";
            // }
            // echo "\n\n\n</pre>";
        ?></div>

        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
                <?php include __DIR__ . '/Includes/filter.html'; ?>

                <div class="card-body">
                  <h5 class="card-title">Sales <span>| Today</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6>145</h6>
                      <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">
                <?php include __DIR__ . '/Includes/filter.html'; ?>

                <div class="card-body">
                  <h5 class="card-title">Revenue <span>| This Month</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                      <h6>$3,264</h6>
                      <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">
                <?php include __DIR__ . '/Includes/filter.html'; ?>

                <div class="card-body">
                  <h5 class="card-title">Customers <span>| This Year</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6>1244</h6>
                      <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">
              <?php include __DIR__ . '/Includes/filter.html'; ?>

                <div class="card-body">
                  <h5 class="card-title">Reports <span>/Today</span></h5>

                  <!-- Line Chart -->
                  <div id="reports-chart"></div>
                  <!-- End Line Chart -->

                  </div>

              </div>
            </div><!-- End Reports -->

            <!-- Recent Sales -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <?php include __DIR__ . '/Includes/filter.html'; ?>

                <div class="card-body">
                  <h5 class="card-title">Recent Sales <span>| Today</span></h5>
                  <table id="recent-sales" class="table table-borderless datatable">
                  </table>
                </div>

              </div>
            </div><!-- End Recent Sales -->

            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <h5 class="card-title">dades rebudes <span>| poblacions</span></h5>
                    <p>
                      <?php $mgr = new SQLManager();
                      $data = $mgr->select("SELECT * FROM towns;");
                      print_r($data);
                      ?>
                    </p>
                    <p>
                    <?php $mgr = new DataLoader();
                      $data = $mgr->getData("towns");
                      echo json_encode($data);
                      ?>
                    </p>
                    <p>password <?php $password="^2025!02!19%encombro:entries$"; echo password_hash($password, PASSWORD_DEFAULT); ?></p>
                </div>
              </div>
            </div>


            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <?php include __DIR__ . '/Includes/filter.html'; ?>

                <div class="card-body">
                    <h5 class="card-title">Poblacions <span>| Hui</span></h5>
                    <table id="towns" class="table table-borderless datatable">
                    </table>
                </div>

              </div>
            </div><!-- End Incoming Data -->

            <!-- Top Selling -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">
                <?php include __DIR__ . '/Includes/filter.html'; ?>
                <div class="card-body pb-0">
                  <h5 class="card-title">Top Selling <span>| Today</span></h5>

                  <table id="top-selling" class="table table-borderless datatable">
                  </table>

                </div>

              </div>
            </div><!-- End Top Selling -->

          </div>
        </div><!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-lg-4">
          
          <!-- Recent Activity -->
          <div class="card">
            <?php include __DIR__ . '/Includes/filter.html'; ?>
            <div class="card-body">
                <h5 class="card-title">Recent Activity <span>| Today</span></h5>
                <div id="recent-activity-container" class="activity"></div><!-- End activity item-->
            </div>
          </div><!-- End Recent Activity -->

          <!-- Budget Report -->
          <div class="card">
            <?php include __DIR__ . '/Includes/filter.html'; ?>

            <div class="card-body pb-0">
              <h5 class="card-title">Budget Report <span>| This Month</span></h5>

              <div id="budget-chart" style="min-height: 400px;" class="new-echart"></div>
            </div>
          </div><!-- End Budget Report -->

          <!-- Website Traffic -->
          <div class="card">
            <?php include __DIR__ . '/Includes/filter.html'; ?>

            <div class="card-body pb-0">
              <h5 class="card-title">Website Traffic <span>| Today</span></h5>

              <div id="traffic-chart" style="min-height: 400px;" class="new-echart"></div>
            </div>
          </div><!-- End Website Traffic -->

          <!-- News & Updates Traffic -->
          <div class="card">
            <?php include __DIR__ . '/Includes/filter.html'; ?>

            <div class="card-body pb-0">
              <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

              <div id="news-updates" class="news">
              </div><!-- End sidebar recent posts-->

            </div>
          </div><!-- End News & Updates -->

        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <?php require_once __DIR__ . "/Includes/footer.php"; ?>
  <script type="module">
    import { SuccessToast } from '/assets/js/notifications/toast.js'
    import { StorageManager } from '/assets/js/storage/storage.manager.js'
    import { demoService } from '/assets/js/api/demo.service.js'
    import { Table } from '/assets/js/tables/table.js'
    import { List } from '/assets/js/lists/list.js'
    import { ChartFactory } from '/assets/js/charts/charts.js'
    import { Image } from '/assets/js/images/image.js'
    import { Anchor } from '/assets/js/anchors/anchor.js'
    import { Span } from '/assets/js/spans/span.js'
    import { Monetary } from '/assets/js/numbers/monetary.js'
    import { formatIsoDate } from '/assets/js/dates/dates.js'

    $(document).ready(function() {
      const promise = demoService.getAllData().then(response => {
        const data = response.data
        
        // Filling tables
        let columns = { id: '#', name: 'nom', zip_code: 'codi postal', county: 'província', created_at: 'data' }
        let formatters = {'codi postal': {constructor: Anchor, href: (href) => {return `/town/${href}`}, className: (_) => {return 'text-warning'}}, 'data': (date) => {return formatIsoDate(date, "%w, %d %b %Y")}}
        new Table("#towns", data.towns, columns, formatters).build();
        
        columns = {id: '#', image_url: 'imatge', product_name: 'nom', price: 'preu', sold: 'existències', revenue: 'ganàncies'}
        new Table("#top-selling", data.top_selling, columns, {'imatge': Image, nom: Anchor}).build()
        
        columns = {sale_id: '#', customer_name: 'nom client', product_name: 'article', price: 'preu', status: 'estat'}
        const custNameAnchor = {constructor: Anchor, className: (cls) => {return cls.length > 5 ? 'text-success' : 'text-warning'}}
        formatters = {'#': Anchor, estat: Span, preu: Monetary, 'nom client': custNameAnchor}
        new Table('#recent-sales', data.recent_sales, columns, formatters).build()
        
        // Filling lists
        new List('#recent-activity-container', data.recent_activities)
        new List('#news-updates', data.news_updates)
        
        // Filling charts
        ChartFactory.createChart('#reports-chart', data.reports).init()
        ChartFactory.createChart('#budget-chart', data.budget).init()
        ChartFactory.createChart('#traffic-chart', data.traffic_sources).init()
        $('.datatable-input[type="search"]').attr('placeholder', 'Cercar...');
      }).catch(error => console.error('something gone wrong', error))
      
      
      const storage = new StorageManager()
      loggedInAtInSec = Number(Date.now() / 100).toFixed(0) - storage.getLocalData('loggedInAt')
      if(loggedInAtInSec < 10) {
        const welcomeToast = new SuccessToast('Benvinguda', 'Has iniciat sessió correctament!')        
        welcomeToast.show()
      }
    })

  </script>
  <?php require_once __DIR__ . "/Includes/footer-logout.html"; ?>

</body>
</html>