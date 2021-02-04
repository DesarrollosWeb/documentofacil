<?php
include_once 'gestoria/constants.php';
include_once 'gestoria/Db.php';
include_once "gestoria/vendor/autoload.php";

const PAGE = "?page=";
$db = DB::getInstance();

$total_procedures = $db->get_scalar("select count(id) from wp_procedure");
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
    $procedures = $db->get_query("SELECT * FROM wp_procedure ORDER BY creation_date LIMIT :start, :end",
        ["start" => $offset, "end" => $items_per_page]);
    krumo($procedures);
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
    <?php foreach ($procedures["data"] as $row): ?>
        <li><strong><?= $row["id"]; ?></strong> <?= $row["creation_date"]; ?></li>
    <?php endforeach; ?>
</ul>
<table>
    <caption>Sonarlint is annoying</caption>
    <thead>
    <tr>
        <th scope="row" colspan="4">Title</th>
    </tr>
    </thead>
    <tr>
        <td><a href="<?= $_SERVER["PHP_SELF"] . "?page=1"; ?>">Primero</a></td>
        <td>
            <a href="<?= $_SERVER["PHP_SELF"] . PAGE . ($current_page - 1 < 1 ? 1 : $current_page - 1); ?>">anterior</a>
        </td>
        <td><a href="<?= $_SERVER["PHP_SELF"] . PAGE . ($current_page + 1); ?>">siguiente</a></td>
        <td><a href="<?= $_SERVER["PHP_SELF"] . PAGE . ($total_pages); ?>">ultimo</a></td>
    </tr>
</table>

</body>
</html>
