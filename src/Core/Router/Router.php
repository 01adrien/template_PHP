<?php

namespace Src\Core\Router;

use Aura\Router\RouterContainer;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Core\Interfaces\RouterInterface;
use Src\Core\Router\Route;
use Src\Core\Attributes\Routes\{
    Base,
    Route as RouteAttribute
};

class Router implements RouterInterface
{
    private $router;

    public function __construct()
    {
        $this->router = new RouterContainer();
    }

    /**
     * add a route to our Router
     *
     * @param  mixed $path
     * @param  mixed $callable
     * @param  mixed $name
     * @param  mixed $httpMethod
     * @return void
     */
    public function addRoute(string $path, $callable, ?string $name = null, string $httpMethod): void
    {
        $method = strtolower($httpMethod);
        $this->router->getMap()->$method($name, $path, $callable);
    }

    /**
     * register route by controller's attributes
     *
     * @param  mixed $controller[]
     * @return void
     */
    public function registerRoutesFromControllersAttributes(array $controllers): void
    {
        foreach ($controllers as $controller) {
            $reflectionController = new \ReflectionClass($controller);
            $classAttributes = $reflectionController->getAttributes(
                Base::class,
                \ReflectionAttribute::IS_INSTANCEOF
            );
            $basePath = "";
            foreach ($classAttributes as $attribute) {
                $p = $attribute->newInstance();
                $basePath .= $p->basePath;
            }
            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(
                    RouteAttribute::class,
                    \ReflectionAttribute::IS_INSTANCEOF
                );
                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $this->addRoute(
                        $basePath . $route->path,
                        [$controller, $method->getName()],
                        $route->name,
                        $route->method
                    );
                }
            }
        }
    }

    /**
     * Check if there is a match route for the request
     *
     * @param  mixed $request
     * @return Route
     */
    public function match(Request $request): ?Route
    {
        $matcher = $this->router->getMatcher();
        $route = $matcher->match($request);
        if ($route) {
            return new Route($route->name, $route->handler, $route->attributes);
        }
        return null;
    }
    /**
     * generate the route url
     *
     * @param  mixed $name
     * @param  mixed $params
     * @return string
     */
    public function generateUrl(string $name, array $params = [], array $queryParams = []): string
    {
        $url = $this->router->getGenerator()->generate($name, $params);
        if (!empty($queryParams)) {
            return $url . '?' . http_build_query($queryParams);
        }
        return $url;
    }

    /**
     * redirect to provided url
     *
     * @param  mixed $path
     * @param  mixed $params
     * @return Response
     */
    public function redirect(string $path, array $params = []): Response
    {
        $redirectUri = $this->generateUrl($path, $params);
        return (new Response())
            ->withStatus(301)
            ->withHeader('location', $redirectUri);
    }
}
