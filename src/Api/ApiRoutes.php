<?php
namespace Backoffice\Api;

use Backoffice\Middlewares\JwtMiddleware;
use Backoffice\Controllers\AuthController;
use Backoffice\Controllers\DemoController;
use Backoffice\Controllers\UsersController;
use Backoffice\Core\RouteEndpoint;

class ApiRoutes
{
    private $routes = [];

    public function __construct()
    {
        $this->routes = [
            // Authentification
            RouteEndpoint::post('/auth/login')->to(AuthController::class, 'login')->resolve(),
            // Users
            RouteEndpoint::get('/users')->to(UsersController::class, 'list')->with([JwtMiddleware::class])->resolve(),
            RouteEndpoint::get('/users/{id}')->to(UsersController::class, 'getUsernameById')->with([JwtMiddleware::class])->resolve(),
            RouteEndpoint::post('/users')->to(UsersController::class, 'create')->with([JwtMiddleware::class])->resolve(),
            RouteEndpoint::post('/users/suggestions')->to(UsersController::class, 'suggest')->with([JwtMiddleware::class])->resolve(),
            RouteEndpoint::patch('/users/{id}')->to(UsersController::class, 'update')->with([JwtMiddleware::class])->resolve(),
            RouteEndpoint::delete('/users/{id}')->to(UsersController::class, 'delete')->with([JwtMiddleware::class])->resolve(),
            // Demo
            RouteEndpoint::get('/miscellaneous')->to(DemoController::class, 'list')/* ->with([JwtMiddleware::class]) */->resolve()
        ];
    }

    /**
     * Handle the request and return a response.
     */
    public function handleRequest($method, $uri)
    {
        // Find the matching route
        foreach ($this->routes as $endpoint) {
            // Check if the method and path match
            if ($method === $endpoint->getMethod() && $this->matchPath($endpoint->getPath(), $uri)) {

                // Execute middlewares
                foreach ($endpoint->getMiddlewares() as $middlewareClass) {
                    $middlewareInstance = new $middlewareClass();
                    if (!$middlewareInstance->handle()) {
                        return [
                            'status' => 401,
                            'body' => ['error' => 'Unauthorized'],
                        ];
                    }
                }

                // Call the controller and return the response
                return $this->callController($endpoint, $uri);
            }
        }

        // If no route matches, return a 404 error
        return [
            'status' => 404,
            'body' => ['error' => 'Route not found'],
        ];
    }

    /**
     * Check if the route path matches the URI.
     */
    private function matchPath($routePath, $uri)
    {
        // Convert the route to a regex to handle dynamic parameters
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        return preg_match("#^$pattern$#", $uri);
    }

    /**
     * Extract dynamic parameters from the URI.
     */
    private function fetchParams($routePath, $uri)
    {
        // Extract dynamic parameters from the URI
        preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
        preg_match('#^' . preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath) . '$#', $uri, $paramValues);

        $params = [];
        foreach ($paramNames[1] as $index => $name) {
            $params[$name] = $paramValues[$index + 1];
        }

        return $params;
    }

    /**
     * Call the controller and return the response.
     */
    private function callController(RouteEndpoint $endpoint, string $uri)
    {
        $controller = $endpoint->getController();
        $action = $endpoint->getAction();
        $params = $this->fetchParams($endpoint->getPath(), $uri);

        // Include the controller and call the method
        if (class_exists($controller)) {
            $controllerInstance = new $controller();
            if (method_exists($controllerInstance, $action)) {
                return call_user_func_array([$controllerInstance, $action], $params);
            }
        }

        // If the controller or action does not exist, return a 404 error
        return [
            'status' => 404,
            'body' => ['error' => 'Controller or action not found'],
        ];
    }
}