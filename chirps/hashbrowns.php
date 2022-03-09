<?php

$gimmie = password_hash('admin12345', PASSWORD_DEFAULT);

echo substr($gimmie, 10, 10);