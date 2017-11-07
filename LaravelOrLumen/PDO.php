<?php

$pdo = new PDO($dsn, $user, $pass);

PDO::exec($statement);

PDO::lastInsertId([$name = null]);

PDO::query($sql);

PDO::prepare($sql);

PDOStatement::execute();


======事务
$pdo->beginTransaction();

$pdo->exec($sql);

$pdo->commit();

$pdo->rollback();


======抛异常
try {

} catch (Exception $e) {

}