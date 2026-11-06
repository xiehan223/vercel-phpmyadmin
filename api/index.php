<?php
// 1. 定义 phpMyAdmin 版本及下载路径
$version = '5.2.3'; // 你可以根据需要修改为最新稳定版
$target_dir = __DIR__ . '/../public';
$archive = __DIR__ . '/../pma.tar.gz';

// 2. 如果尚未下载和解压，则执行初始化（Vercel 部署时或首次运行时）
if (!file_exists($target_dir . '/index.php')) {
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    // 下载 phpMyAdmin 压缩包
    $url = "https://phpmyadmin.net{$version}/phpMyAdmin-{$version}-all-languages.tar.gz";
    file_put_contents($archive, fopen($url, 'r'));
    
    // 解压文件
    $pma_dir = __DIR__ . '/../pma_tmp';
    if (!is_dir($pma_dir)) {mkdir($pma_dir, 0755, true);}
    
    $phar = new PharData($archive);
    $phar->extractTo($pma_dir);
    
    // 移动文件到 public 目录
    $extracted_folder = $pma_dir . "/phpMyAdmin-{$version}-all-languages";
    $files = scandir($extracted_folder);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        rename($extracted_folder . '/' . $file, $target_dir . '/' . $file);
    }
    
    // 清理临时文件
    @unlink($archive);
    @rmdir($extracted_folder);
    @rmdir($pma_dir);
}

// 3. 动态生成 phpMyAdmin 的配置文件 config.inc.php
$config_path = $target_dir . '/config.inc.php';

// 从 Vercel 环境变量中读取 TiDB Cloud 的连接信息
$db_host = getenv('TIDB_HOST') ?: 'localhost';
$db_port = getenv('TIDB_PORT') ?: '4000';

$config_content = "<?php
\$cfg['blowfish_secret'] = 'k9#mQ2xL!9zP$vW7bN4uE1cR0sT5yX8z';
\$i = 0;
\$i++;
\$cfg['Servers'][\$i]['auth_type'] = 'cookie';
\$cfg['Servers'][\$i]['host'] = '{$db_host}';
\$cfg['Servers'][\$i]['port'] = '{$db_port}';
\$cfg['Servers'][\$i]['compress'] = false;
\$cfg['Servers'][\$i]['AllowNoPassword'] = false;

// 解决 TiDB 与传统 MySQL 某些元数据查询不完全兼容的进阶配置
\$cfg['Servers'][\$i]['DisableIS'] = true; 
";

file_put_contents($config_path, $config_content);

// 4. 将请求转发给 phpMyAdmin 实际的入口
$_SERVER['SCRIPT_FILENAME'] = $target_dir . '/index.php';
chdir($target_dir);
require($target_dir . '/index.php');
