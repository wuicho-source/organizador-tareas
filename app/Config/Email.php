<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    public string $fromEmail  = 'wuicholanguila12@gmail.com';
    public string $fromName   = 'Organizador de Tareas';
    public string $recipients = '';

    public string $userAgent = 'CodeIgniter';
    public string $protocol  = 'smtp';
    public string $mailPath  = '/usr/sbin/sendmail';

    // Configuración SMTP para Gmail
    public string $SMTPHost   = 'smtp.gmail.com';
    public string $SMTPUser   = 'wuicholanguila12@gmail.com';
    public string $SMTPPass   = 'klnl sqdz xnry nngv'; 
    public int    $SMTPPort   = 587;
    public int    $SMTPTimeout = 10;
    public bool   $SMTPKeepAlive = false;
    public string $SMTPCrypto = 'tls';

    public bool   $wordWrap  = true;
    public int    $wrapChars = 76;
    public string $mailType  = 'html';
    public string $charset   = 'UTF-8';
    public bool   $validate  = false;
    public int    $priority  = 3;
    public string $CRLF      = "\r\n";
    public string $newline   = "\r\n";
    public bool   $BCCBatchMode = false;
    public int    $BCCBatchSize = 200;
    public bool   $DSN       = false;
}
