<?php
namespace Backoffice\Core;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use Backoffice\Views\Errors\ErrorPage;

class Router
{
    private $routes = [];
    private $viewsPath;

    public function __construct(string $viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '\\/');
        $this->add('', 'home.php');
        $this->add('home', 'home.php');
    }

    public function add(string $route, $handler): void
    {
        $cleanRoute = trim($route, '/');
        $this->routes[$cleanRoute] = $handler;
    }

    public function autoRegisterViews(?string $directory=null): void
    {
        if (!$directory) $directory = $this->viewsPath;

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
        );

        $exclusions = ['test.secret.php', 'x-debug.php', '/includes/'];

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $fullPath = $file->getPathname();
                $relativePath = str_replace([$directory, '\\'], ['', '/'], $fullPath);
                $routePath = trim(str_replace('.php', '', $relativePath), '/');

                foreach ($exclusions as $excl) {
                    if (str_contains($routePath, $excl)) continue 2;
                }

                $seoRoute = str_replace('_', '-', $routePath);
                $this->add($seoRoute, $relativePath . '');
            }
        }
    }

    public function dispatch(): void
    {
        $requestUrl = trim($_GET['url'] ?? '', '/');

        // API Handling
        if (str_starts_with($requestUrl, 'api/')) {
            $this->handleApi($requestUrl);
            return;
        }

        // Registered routes
        if (isset($this->routes[$requestUrl])) {
            $this->handleRoute($this->routes[$requestUrl]);
        } else {
            ErrorPage::Err404();
        }
    }

    private function handleRoute($handler): void
    {
        if (is_callable($handler)) {
            call_user_func($handler);
        } elseif (is_string($handler)) {
            $this->renderView($handler);
        }
    }

    private function renderView(string $viewFile, int $status = 200): void
    {
        $fullPath = $this->viewsPath . '/' . $viewFile;
        
        if (file_exists($fullPath)) {
            http_response_code($status);
            include $fullPath;
        } else {
            ErrorPage::Err500();
        }
    }

    private function handleApi(string $endpoint): void
    {
        $apiFile = __DIR__ . '/../../public/api/service.php';
        if (file_exists($apiFile)) {
            header('Content-Type: application/json');
            include $apiFile;
        } else {
            $page = new ErrorPage(404, 'API endpoint not found');
            $page->render();
        }
    }
}
