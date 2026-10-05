<?php
namespace App\Core;
use App\Core\Response;
final class Router
{
    /**
     * @param array $routes    List of [method, path, [Class::class, 'action']]
     * @param array $factories [Class::class => fn() => new Class(...)]
     * @param View  $view      Used to render the 404 page
     */
    public function __construct(
        private array $routes,
        private array $factories,
        private View $view,
    ) {}

    /**
     * Trouve la route correspondante, appelle son contrôleur, renvoie la Response.
     */
    public function dispatch(Request $request): Response
    {
        foreach ($this->routes as [$method, $path, [$class, $action]]) {
            if ($method === $request->getMethod() && $path === $request->getPath()) {
                $controller = ($this->factories[$class])();

                return $controller->$action($request);
            }
        }

        return $this->view->render('404', [
            'title' => 'Page introuvable',
            'path'  => $request->getPath(),
        ], 404);
    }
}