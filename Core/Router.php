<?php


namespace Core;

use Core\Middleware\Middleware;

class Router
{
  protected $routes = [];


  public function add($method, $uri, $controller)
  {
    $this->routes[] = [
      'uri' => $uri,
      'controller' => $controller,
      'method' => $method,
      'middleware' => null
    ];

    return $this;
  }

  public function get($uri, $controller)
  {
    return $this->add('GET', $uri, $controller);
  }

  public function post($uri, $controller)
  {
    return $this->add('POST', $uri, $controller);
  }

  public function delete($uri, $controller)
  {
    return $this->add('DELETE', $uri, $controller);
  }

  public function patch($uri, $controller)
  {
    return $this->add('PATCH', $uri, $controller);
  }

  public function put($uri, $controller)
  {
    return $this->add('PUT', $uri, $controller);
  }

  public function only($key)
  {
    $this->routes[array_key_last($this->routes)]['middleware'] = $key;

    return $this;
  }

  public function route($uri, $method)
  {

    foreach ($this->routes as $route) {
      if ($route['method'] !== strtoupper($method)) {
        continue; // Skip or proceed to next iteration if mismatched and exit early if finding no match
      }

      $uri = trim($uri, '/'); // remove the '/' of example: /users => users
      $routeUri = trim($route['uri'], '/');

      $uriParts = explode('/', $uri); // seperate to each part in between to the seperator we use '/' and stored in an array
      $routeParts = explode('/', $routeUri); // (2)




      if (count($uriParts) !== count($routeParts)) {
        continue; // skip if they have different number of parts
      }

      $params = [];
      $isRouteMatch = true;

      foreach ($routeParts as $index => $part) {
        if (preg_match('/^{(.+)}$/', $part, $matches)) {
          // This is the Dynamic part: which grab param name example:'user'
          $paramName = $matches[1]; // Contains matched text only like we need e.g 'user' not full text '{user}'
          $params[$paramName] = $uriParts[$index];
        } else if ($part !== $uriParts[$index]) {
          $isRouteMatch = false; // this part is if the static part is not match example users !== index
          break;
        }
      }


      if ($isRouteMatch) {
        Middleware::resolve($route['middleware']);
        return $this->handleRoute($route, $params);
      }
    }
    $this->abort();
  }

  private function handleRoute($route, $params)
  {
    if (strpos($route['controller'], '@') !== false) {
      return $this->callController($route['controller'], $params);
    }

    return require base_path('Http/controllers/' . $route['controller']);
  }

  private function callController($controllerAction, $params = [])
  {


    $seperation = explode('@', $controllerAction);


    $controllerName = 'Http\\controllers\\' . $seperation[0];
    $methodName = $seperation[1];

    $controller = new $controllerName();

    if (empty($params)) {
      $controller->$methodName();
    } else {
      $controller->$methodName($params['user']);
    }
  }

  public function previousUrl()
  {
    return $_SERVER['HTTP_REFERER'];
  }


  protected function abort($code = 404)
  {
    http_response_code($code);

    require base_path("views/{$code}.php");

    die();
  }
}
