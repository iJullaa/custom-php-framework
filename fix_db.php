<?php

$config = require __DIR__ . '/config/config.php';

echo "------------------------------------------------\n";
echo "NAPRAWA BAZY DANYCH\n";
echo "------------------------------------------------\n";

$sqlFile = __DIR__ . '/sql/01-post.sql';
if (!file_exists($sqlFile)) {
    die("BŁĄD: Nie znaleziono pliku $sqlFile. Sprawdź czy jesteś w dobrym folderze!\n");
}

try {
    $pdo = new PDO($config['db_dsn']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Połączono z bazą: " . $config['db_dsn'] . "\n";

    $sql = file_get_contents($sqlFile);
    $pdo->exec($sql);
    echo "SUKCES: Tabela 'post' została utworzona.\n";

    $sqlProduct = "
        CREATE TABLE IF NOT EXISTS product (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            price REAL NOT NULL,
            description TEXT
        );
        INSERT INTO product (name, price, description) VALUES ('Testowy Produkt', 123.45, 'To jest opis testowy');
    ";
    $pdo->exec($sqlProduct);
    echo "SUKCES: Tabela 'product' została utworzona (wymagane do zadania).\n";

} catch (PDOException $e) {
    echo "BŁĄD KRYTYCZNY: " . $e->getMessage() . "\n";
}
echo "------------------------------------------------\n";