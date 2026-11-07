<?php
// 显式声明全局常量，防止某些环境下的安全拦截
define('PHPMYADMIN', true);

$base_dir = dirname(__DIR__) . '/public';

// 强制切换工作目录到 public
chdir($base_dir);

// 使用绝对路径直接引入 phpMyAdmin 的主入口，防止相对路径丢失
require($base_dir . '/index.php');
