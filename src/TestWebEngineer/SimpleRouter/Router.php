<?php
declare(strict_types = 1);
namespace TestWebEngineer\SimpleRouter;

use TestWebEngineer\JsonApi\Controller\AbstractApiController;
use TestWebEngineer\SimpleRouter\Exception\IncorrectJsonInputException;
class Router
{

    /**
     *
     * @var array
     */
    private $routes = [];

    /**
     *
     * @var AbstractApiController[]
     */
    private $controllers = [];

    /**
     *
     * @param array $routes
     * @param AbstractApiController[] $controllers
     */
    public function __construct(array $routes, array $controllers)
    {
        $this->routes = $routes;
        $this->controllers = $controllers;
    }

    /**
     * @param string $url
     * @param string $method
     * @return bool
     */
    public function execute(string $url, string $method) : bool
    {
        foreach ($this->routes as $route) {
            $arguments = [];
            $regexp = '/^'.str_replace('/', '\/', $route['path']).'$/';
            if ($route['method'] == $method && preg_match($regexp, $url, $arguments)) {
                array_shift($arguments);
                $controller = $this->controllers[$route['controller'].'Controller'];
                $action = $route['action'].'Action';
                try{
                    switch ($method) {
                        case 'DELETE':
                            $inputValues = [];
                            break;
                        case 'GET':
                            $inputValues = [];
                            break;
                        default:
                            $inputValues = ($this->cleanInputs(file_get_contents("php://input")));
                    }
                }
                catch (IncorrectJsonInputException $e){
                    $response = new Response();
                    $response->setStatus(400)->setContentType('application/json')->setContent(json_encode(
                        ['Error' => 'Incorrect Json Input Values']
                    ));

                    return $this->response($response);
                }

                $request = new Request($method, array_values($arguments), $inputValues ?? []);
                $response = $controller->{$action}($request);

                return $this->response($response);
            }
        }
        $response = new Response();
        $response->setStatus(404)->setContentType('application/json')->setContent(json_encode(
            ['Error' => 'Route Match Or Method Not Found']
        ));

        return $this->response($response);
    }

    /**
     * @param ResponseInterface $response
     * @return bool
     */
    protected function response(ResponseInterface $response) : bool
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: ".$response->getContentType());
        header("HTTP/1.1 ".$response->getStatus()." ".$this->requestStatus($response->getStatus()));

        echo $response->getContent();

        return true;
    }

    /**
     * not exhaustiv list of request status
     *
     * @param int $code
     * @return string
     */
    protected function requestStatus(int $code)
    {
        $status = array(
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            204 => 'No Content',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            412 => 'Precondition Failed',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable',
        );

        return ($status[$code]) ?? $status[500];
    }

    /**
     *
     * @param string|array $data
     * @return array
     * @throws IncorrectJsonInputException
     */
    protected function cleanInputs($data) : array
    {
        $clean_input = null;
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = json_decode($this->cleanInputs($v), true);
            }
        } elseif (trim($data) != '') {
            $clean_input = json_decode(strip_tags($data), true);
        }

        if($clean_input === null){
            throw new IncorrectJsonInputException();
        }

        return $clean_input;
    }
}
