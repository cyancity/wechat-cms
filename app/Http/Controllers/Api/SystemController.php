<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/29
 * Time: 下午8:13
 */

namespace App\Http\Controllers\Api;


class SystemController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSystemInfo()
    {
        $pdo = \DB::connection()->getPdo();

        $version = $pdo->query('select version()')->fetchColumn();

        $data = [
            'server' => $_SERVER['SERVER_SOFTWARE'],
            'http_host' => $_SERVER['HTTP_HOST'],
            'remote_host' => isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'php' => phpversion(),
            'sapi_name' => php_sapi_name(),
            'extension' => implode(",", get_loaded_extensions()),
            'db_connection' => isset($_SERVER['DB_CONNECTION']) ? $_SERVER['DB_CONNECTION'] : 'Secret',
            'db_database' => isset($_SERVER['DB_DATABASE']) ? $_SERVER['DB_DATABASE'] : 'Secret',
            'db_version' => $version
        ];

        return $this->response->json($data);
    }
}