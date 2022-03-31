<?php
session_start();

use App\Controllers\ProductsController;
use App\Controllers\WelcomeController;
use App\Redirect;
use App\Repositories\Product\MySqlProductRepository;
use App\Repositories\Product\ProductRepository;
use App\Views\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once 'vendor/autoload.php';

$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
//    StoreProductService::class => function (ContainerInterface $container) {
//    return new StoreProductService($container->get(CsvProductRepository::class));
//    },
    ProductRepository::class => DI\create(MySqlProductRepository::class)
]);
$container = $builder->build();


$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/', [WelcomeController::class, 'opening']);

    $r->addRoute('GET', '/products', [ProductsController::class, 'index']);
    $r->addRoute('GET', '/products/{id:\d+}', [ProductsController::class, 'show']);

    $r->addRoute('GET', '/products/add', [ProductsController::class, 'add']);
    $r->addRoute('POST', '/products', [ProductsController::class, 'store']);

    $r->addRoute('POST', '/products/{id:\d+}/buy', [ProductsController::class, 'buy']);
    $r->addRoute('POST', '/products/{id:\d+}/buy/confirm', [ProductsController::class, 'confirm']);
    $r->addRoute('POST', '/products/{id:\d+}/buy/pay', [ProductsController::class, 'pay']);

});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        var_dump("404 Not Found");
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        var_dump("405 Method Not Allowed");
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $controller = $handler[0];
        $method = $handler[1];
        $vars = $routeInfo[2];

        $response = ($container->get($controller))->$method($vars);

        $loader = new FilesystemLoader('app/Views');
        $twig = new Environment($loader);

        if ($response instanceof View) {
            echo $twig->render($response->getPath() . '.html', $response->getVariables());
        }
        if ($response instanceof Redirect) {
            header('Location: ' . $response->getLocation());
            exit;
        }
        break;
}