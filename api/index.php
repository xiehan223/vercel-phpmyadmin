<?php
// 1. 强行开启所有 PHP 错误显示，直接把闪退原因打印在网页上
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 2. 严格定义绝对路径
$base_dir = dirname(__DIR__) . '/public';

// 3. 将 public 目录塞进系统的包含路径列表中，确保内部包含不迷路
set_include_path(get_include_path() . PATH_SEPARATOR . $base_dir);

// 4. 强行切换工作目录
chdir($base_dir);

// 5. 引入真正的入口
if (!file_exists($base_dir . '/index.php')) {
    die("核心错误：在路径 " . $base_dir . "/index.php 找不到 phpMyAdmin 主入口文件！");
}

require($base_dir . '/index.php');
