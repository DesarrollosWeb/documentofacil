<?php

//include_once "wp-load.php";
const PAGE = "?page=";
include_once "gestoria/vendor/autoload.php";
include_once "gestoria/constants.php";
include_once "gestoria/Db.php";
include_once "gestoria/Procedure.php";

//krumo($_SERVER);
//$user = wp_get_current_user();
//$_SESSION["email"] = $user->user_email;
$_SESSION["email"] = "anyulled@gmail.com";
$row = new Procedure($_SESSION["email"]);
$current_page = 1;
$items_per_page = 5;
$total_pages = 0;
if (array_key_exists("page", $_GET)) {
    $current_page = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT);
}
$offset = ($current_page - 1) * $items_per_page;
try {
    $procedure_data = $row->get_orders_current_user(["start" => $current_page, "items" => $items_per_page]);
    krumo($procedure_data);
    $total_pages = ceil($procedure_data["stats"]["total_records"] / $items_per_page);
} catch (Exception $e) {
    if (WP_DEBUG) {
        krumo($e);
    }
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=1024, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" media="all" type="text/css"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <title>Área de Trámites</title>
</head>
<body>
<?php //get_header(); ?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4"><?= $text["procedure_title"]; ?></h1>
    </div>
    <?php if (count($procedure_data["data"]) > 0): ?>
        <table class="table">
            <caption><?= $text["procedure_list"]; ?></caption>
            <thead>
            <tr>
                <th id="1" scope="col"><?= $text["name"]; ?></th>
                <th id="2" scope="col"><?= $text["user"] ?></th>
                <th id="3" scope="col"><?= $text["creation_date"] ?></th>
                <th id="4" scope="col"><?= $text["status"] ?></th>
                <th id="4" scope="col"><?= $text["action"] ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($procedure_data["data"] as $row): ?>
                <tr>
                    <td><?= $row["name"]; ?> </td>
                    <td><?= $row["user"]; ?> </td>
                    <td><?= $row["creation_date"]->format("d-m-Y"); ?> </td>
                    <td><p class="badge badge-secondary"><?= $row["status"]; ?> </p></td>
                    <td><a class="btn btn-primary"
                           href="formulario.php?procedure_id=<?= $row["id"]; ?>"><?= $text["open"] ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="5">
                    <div style="text-align: right;">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end">
                                <li class="page-item">
                                    <a class="page-link" href="<?= $_SERVER["PHP_SELF"] . PAGE . 1; ?>"
                                       tabindex="-1"><?= $text["first"]; ?></a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                                         href="<?= $_SERVER["PHP_SELF"] . PAGE . ($current_page - 1 < 1 ? 1 : $current_page - 1); ?>"><?= $text["previous"]; ?></a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                                         href="<?= $_SERVER["PHP_SELF"] . PAGE . ($current_page + 1); ?>"><?= $text["next"]; ?></a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                                         href="<?= $_SERVER["PHP_SELF"] . PAGE . ($total_pages); ?>"><?= $text["last"]; ?></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <?php if ($user->ID == 0): ?>
            <div class="alert alert-info">
                <h4><?= $text["not_logged_in"]; ?></h4>
                <?php wp_login_form(); ?>
            </div>
        <?php endif; ?>
        <div class="alert alert-info" role="alert">
            <?= $text["user_with_no_orders"]; ?>
        </div>
    <?php endif; ?>
</div>
<?php //get_footer(); ?>
</body>
</html>
