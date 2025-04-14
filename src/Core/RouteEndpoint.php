<?php

namespace Backoffice\Core;

use Backoffice\Core\Exceptions\InvalidRouteException;
use Backoffice\Core\Controller;

/**
 * Represents a route endpoint with its configuration.
 */
class RouteEndpoint
{
    /** @var string HTTP method (GET, POST, etc.) */
    private string $method;

    /** @var string Route path (ex: /users/{id}) */
    private string $path;

    /** @var string Controller class */
    private string $controller;

    /** @var string Controller method to call */
    private string $action;

    /** @var array Middlewares to apply */
    private array $middlewares = [];

    public function __construct(string $method, string $path)
    {
        $this->method = strtoupper($method);
        $this->path = $path;
    }

    /**
     * Defines the controller and action for this route.
     */
    public function to(string $controller, string $action): self
    {
        $this->controller = $controller;
        $this->action = $action;
        return $this;
    }

    /**
     * Adds middlewares to the route.
     */
    public function with(array $middlewares): self
    {
        $this->middlewares = array_merge($this->middlewares, $middlewares);
        return $this;
    }

    /**
     * Validates and returns the route configuration.
     * @throws InvalidRouteException
     */
    public function resolve(): RouteEndpoint
    {
        if (!in_array($this->method, ["GET", "PATCH", "POST", "PUT", "DELETE"])) {
            throw new InvalidRouteException("Unknown HTTP method");
        }
        if (!class_exists($this->controller)) {
            throw new InvalidRouteException("Controller {$this->controller} not found");
        }

        if (!method_exists($this->controller, $this->action)) {
            throw new InvalidRouteException("Action {$this->action} not found in {$this->controller}");
        }

        return $this;
    }

    // Static factories for fluent syntax
    // ----------------------------------
    public static function get(string $path): self
    {
        return new self('GET', $path);
    }

    public static function post(string $path): self
    {
        return new self('POST', $path);
    }

    public static function put(string $path): self
    {
        return new self('PUT', $path);
    }

    public static function delete(string $path): self
    {
        return new self('DELETE', $path);
    }

    public static function patch(string $path): self
    {
        return new self('PATCH', $path);
    }

    // Getters
    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getController(): string
    {
        return $this->controller;
    }
}