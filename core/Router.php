<?php

/* clase router
    Se va a encargar de dar de alta o definir las URL o puntos de entrada por donde se entra a nuestra app. endpoints
    Dirige las solicitudes a las funciones o controladores seguncorresponda segun la URL y el método usado por el cliente
*/

class Router
{
    protected $routes = array(); //Donde definimos nuestras rutas
    protected $params = array();

    //En nuestro array de rutas iremos añadiendo mas rutas + los parametros
    public function add($route, $params)
    {
        $this->routes[$route] = $params;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function matchRoute($url)
    {
        foreach ($this->routes as $route => $params) {
            $pattern = str_replace(['{id}', '/'], ['([0-9]+)', '\/'], $route);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $url['path'])) {

                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function getParams()
    {
        return $this->params;
    }
}
