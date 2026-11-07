<?php
declare(strict_types=1);

$cfg['blowfish_secret'] = 'k9#mQ2xL!9zP$vW7bN4uE1cR0sT5yX8z'; // 已经帮你生成好的32位密钥

$i = 0;
$i++;

/* Authentication type */
$cfg['Servers'][$i]['auth_type'] = 'cookie';

/* Server parameters */
// 从 Vercel 环境变量中动态读取主机名和端口
$cfg['Servers'][$i]['host'] = getenv('TIDB_HOST') ?: 'localhost';
$cfg['Servers'][$i]['port'] = getenv('TIDB_PORT') ?: '4000';
$cfg['Servers'][$i]['compress'] = false;
$cfg['Servers'][$i]['AllowNoPassword'] = false;

/* 关键：优化 TiDB 兼容性 */
$cfg['Servers'][$i]['DisableIS'] = true; 

/* 临时目录配置（避开只读文件系统报错） */
$cfg['TempDir'] = '/tmp';
