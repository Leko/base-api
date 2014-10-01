<?php
if (!function_exists('getallheaders')) 
{ 
    function getallheaders() 
    { 
           $headers = ''; 
       foreach ($_SERVER as $name => $value) 
       { 
           if (substr($name, 0, 5) == 'HTTP_') 
           { 
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
           } 
       } 
       return $headers; 
    } 
}

parse_str(file_get_contents('php://input'), $put_param);

$method = $_SERVER['REQUEST_METHOD'];

echo json_encode(array(
    'method'  => $method,
    'request' => $_REQUEST,
    'get'     => $_GET,
    'post'    => $_POST,
    'put'     => ($method === 'PUT' ? $put_param : array()),
    'delete'  => ($method === 'DELETE' ? $put_param : array()),
    'patch'   => ($method === 'PATCH' ? $put_param : array()),
    'header'  => getallheaders(),
));
