<?php
include_once "gestoria/vendor/autoload.php";

$pdo = new PDO("mysql:host=localhost", "test", "12345678");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

$count_procedures_query = $pdo->query("select count(id) as total from gestoria.`procedure`");
$total_procedures = $count_procedures_query->fetchColumn();
$items_per_page = 5;
$total_pages = ceil($total_procedures / $items_per_page);
$current_page = 1;
if (array_key_exists("page", $_GET)) {
    $current_page = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT);
}
$offset = ($current_page - 1) * $items_per_page;

echo "<br/> total pages: " . $total_pages;
echo "<br/> Current page: " . $current_page;
echo "<br/>total procedures: " . $total_procedures;
echo "<br/> offset: " . $offset;

try {
    $procedure_query = $pdo->prepare("SELECT * FROM gestoria.procedure ORDER BY creation_date LIMIT :start, :end");
    $procedure_query->execute(["start" => $offset, "end" => $items_per_page]);
    $procedure_query->setFetchMode(PDO::FETCH_ASSOC);
    $procedures = $procedure_query->fetchAll();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=1080, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagination</title>
</head>
<body>
<ul>
    <?php foreach ($procedures as $row): ?>
        <li><strong><?= $row["id"]; ?></strong> <?= $row["creation_date"]; ?></li>
    <?php endforeach; ?>
</ul>
<table>
    <tr>
        <td><a href="<?= $_SERVER["PHP_SELF"] . "?page=1"; ?>">Primero</a></td>
        <td>
            <a href="<?= $_SERVER["PHP_SELF"] . "?page=" . ($current_page - 1 < 1 ? 1 : $current_page - 1); ?>">anterior</a>
        </td>
        <td><a href="<?= $_SERVER["PHP_SELF"] . "?page=" . ($current_page + 1); ?>">siguiente</a></td>
        <td><a href="<?= $_SERVER["PHP_SELF"] . "?page=" . ($total_pages); ?>">ultimo</a></td>
    </tr>
</table>

</body>
</html>
