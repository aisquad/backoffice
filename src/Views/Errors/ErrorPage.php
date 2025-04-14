<?php 
namespace Backoffice\Views\Errors;

require_once __DIR__ . '/../../../vendor/autoload.php';


class ErrorPage
{
    private $code;    // HTTP status code (e.g., 404, 500)
    private $message; // Error message to display

    /**
     * Constructor to initialize the error page with a code and a message.
     *
     * @param int $code The HTTP status code.
     * @param string $message The error message to display.
     */
    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * Render the error page as an HTML response.
     */
    public function render()
    {
        // Set the HTTP response code
        http_response_code($this->code);

        // Include the HTML header
        require_once __DIR__ . "/../Includes/head.php";

        // Output the error page content
        ?>
        <body>
          <main>
            <div class="container">
              <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                <h1><?= htmlspecialchars($this->code) ?></h1>
                <h2><?= htmlspecialchars($this->message) ?></h2>
                <a class="btn" href="/">Back to home</a>
                <img src="/assets/img/not-found.svg" class="img-fluid py-5" alt="Error <?= htmlspecialchars($this->code) ?>">
                <div class="credits">
                  Designed by Copyl&#x259;ft
                </div>
              </section>
            </div>
          </main><!-- End #main -->

          <?php require_once __DIR__ . "/../Includes/footer-min.php"; ?>
        </body>
        </html>
        <?php
    }

    /**
     * Render the error as a JSON response.
     */
    public function renderAsJson()
    {
        // Set the HTTP response code
        http_response_code($this->code);

        // Set the content type to JSON
        header('Content-Type: application/json');

        // Output the JSON response with the error details
        echo json_encode([
            'error' => [
                'code' => $this->code,
                'message' => $this->message
            ]
        ]);

        // Terminate script execution to prevent further output
        exit;
    }

    /**
     * Static method to handle a 400 Bad Request error.
     *
     * @param bool $asJson Whether to render as JSON or HTML.
     */
    public static function Err400(bool $asJson = false)
    {
        $error = new self(400, "Bad Request");
        if ($asJson) {
            $error->renderAsJson();
        } else {
            $error->render();
        }
    }

    /**
     * Static method to handle a 401 Unauthorized error.
     *
     * @param bool $asJson Whether to render as JSON or HTML.
     */
    public static function Err401(bool $asJson = false)
    {
        $error = new self(401, "Unauthorized");
        if ($asJson) {
            $error->renderAsJson();
        } else {
            $error->render();
        }
    }

    /**
     * Static method to handle a 403 Forbidden error.
     *
     * @param bool $asJson Whether to render as JSON or HTML.
     */
    public static function Err403(bool $asJson = false)
    {
        $error = new self(403, "Forbidden");
        if ($asJson) {
            $error->renderAsJson();
        } else {
            $error->render();
        }
    }

    /**
     * Static method to handle a 404 Not Found error.
     *
     * @param bool $asJson Whether to render as JSON or HTML.
     */
    public static function Err404(bool $asJson = false)
    {
        $error = new self(404, "Not Found");
        if ($asJson) {
            $error->renderAsJson();
        } else {
            $error->render();
        }
    }

    /**
     * Static method to handle a 500 Internal Server Error.
     *
     * @param bool $asJson Whether to render as JSON or HTML.
     */
    public static function Err500(bool $asJson = false)
    {
        $error = new self(500, "Internal Server Error");
        if ($asJson) {
            $error->renderAsJson();
        } else {
            $error->render();
        }
    }
}
