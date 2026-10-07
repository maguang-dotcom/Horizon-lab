<?php
try {
  $p = new PDO('mysql:host=127.0.0.1;port=3306;dbname=horizon_lab', 'root', '', [PDO::ATTR_TIMEOUT => 5]);
  echo "connected\n";
} catch (Throwable $e) {
  echo $e->getMessage(), "\n";
}
