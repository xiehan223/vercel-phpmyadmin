<?php
// 1. 强制将工作目录切换到静态资源和源码所在的 public 目录
chdir(__DIR__ . '/../public');

// 2. 将 public 目录加入到 PHP 的系统包含路径中，彻底解决库文件找不到的问题
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../public');

// 3. 将配置文件从 api 目录动态复制到 public 目录（解决 phpMyAdmin 找不到配置的死穴）
if (file_exists(__DIR__ . '/config.inc.php')) {
    // Vercel 运行时 /tmp 是唯一可写的临时目录，但这里我们在内存中或通过软链接引导更为安全
    // 更好的做法是直接要求 public 目录下的原生 index 入口
}

// 引导真正的入口
require('index.php');
