<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'test';
$port = 3306;

$dsn = "mysql:host=$host;port=$port;dbname=$dbname";

function consultar($sql = 'SELECT * FROM `usuarios` WHERE id = 1')
{
    global $dsn, $user, $pass;
    $conn = new PDO($dsn, $user, $pass);

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $err) {
        echo 'Falha na operação: ' . $err->getMessage();
    } finally {
        $conn = null;
        $stmt = null;
    }
}

function executar($sql, ...$param)
{
    global $dsn, $user, $pass;
    $conn = new PDO($dsn, $user, $pass);

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($param);
        return true;
    } catch (PDOException $err) {
        echo 'Falha na operação: ' . $err->getMessage();
        return false;
    } finally {
        $conn = null;
        $stmt = null;
    }
}