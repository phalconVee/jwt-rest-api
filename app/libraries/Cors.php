<?php
/**
 * Created by PhpStorm.
 * User: phalconVEE
 * Date: 08/06/2018
 * Time: 3:23 PM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Cors
{
    public function allowCORS()
    {
        header("Access-Control-Allow-Origin: * ");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            die();
        }

    }

}