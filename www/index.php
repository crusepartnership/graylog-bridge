<?php
namespace LogBridge;
require __DIR__ . '/../vendor/autoload.php';
$config_path = __DIR__ .'/../conf/config.yml';
if (!file_exists($config_path)) {
    throw new \Exception("Config not found at $config_path");
}
$config = yaml_parse_file($config_path);
$server = new Server();
$server->setLogServer($config['graylog-server']);
$server->receive($_POST);
$server->respond();
