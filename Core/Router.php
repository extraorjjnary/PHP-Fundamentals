<?php


namespace Core;

use Core\Middleware\Middleware;

class Router
{
  protected static $routes = [];


  public static function add($method, $uri, $controller)
  {
    static::$routes[] = [
      'uri' => $uri,
      'controller' => $controller,
      'method' => $method,
      'middleware' => null
    ];

    return new static;
  }

  public static function get($uri, $controller)
  {
    return static::add('GET', $uri, $controller);
  }

  public static function post($uri, $controller)
  {
    return static::add('POST', $uri, $controller);
  }

  public static function delete($uri, $controller)
  {
    return static::add('DELETE', $uri, $controller);
  }

  public static function patch($uri, $controller)
  {
    return static::add('PATCH', $uri, $controller);
  }

  public static function put($uri, $controller)
  {
    return static::add('PUT', $uri, $controller);
  }

  public static function only($key)
  {
    static::$routes[array_key_last(static::$routes)]['middleware'] = $key;

    return new static;
  }

  public static function route($uri, $method)
  {

    foreach (static::$routes as $route) {
      if ($route['method'] !== strtoupper($method)) {
        continue; // Skip or proceed to next iteration if mismatched and exit early if finding no match
      }

      $uri = trim($uri, '/'); // remove the '/' only at edges so the explode cannot return empty value in an array e.g: /users/5 → users/5

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
        return static::handleRoute($route, $params);
      }
    }
    static::abort();
  }

  private static function handleRoute($route, $params)
  {
    if (strpos($route['controller'], '@') !== false) {
      return static::callController($route['controller'], $params);
    }

    return require base_path('Http/controllers/' . $route['controller']);
  }

  private static function callController($controllerAction, $params = [])
  {


    $separation = explode('@', $controllerAction);


    $controllerName = 'Http\\controllers\\' . $separation[0];
    $methodName = $separation[1];

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


  protected static function abort($code = 404)
  {
    http_response_code($code);

    require base_path("views/{$code}.php");

    die();
  }
}
