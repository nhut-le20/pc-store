<?php
require __DIR__ . '/vendor/autoload.php';

use App\Core\Mailer;

Mailer::send(
    "hn4793813@gmail.com",
    "Test",
    "Test mail",
    "<h2>Gửi thành công!</h2>"
);