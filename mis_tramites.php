<?php
//if (!IS_DEVELOPMENT) {
include_once "wp-load.php";
//}
const PAGE = "?page=";
//region Includes
include_once "gestoria/vendor/autoload.php";
include_once "gestoria/constants.php";
include_once "gestoria/Db.php";
include_once "gestoria/Procedure.php";
//endregion

if (!IS_DEVELOPMENT) {
    $user = wp_get_current_user();
    $_SESSION["email"] = $user->user_email;
} else {
    $_SESSION["email"] = "anyulled@gmail.com";
}
$procedure = new Procedure($_SESSION["email"]);
$current_page = 0;
$items_per_page = 10;
$total_pages = 0;
if (array_key_exists("page", $_GET)) {
    $current_page = filter_var($_GET["page"], FILTER_SANITIZE_NUMBER_INT);
}

$offset = ($current_page - 1) * $items_per_page;
try {
    $procedure_data = $procedure->get_orders_current_user(["start" => $current_page, "items" => $items_per_page]);
    $total_pages = ceil($procedure_data["stats"]["total_records"] / $items_per_page);
} catch (Exception $e) {
    if (IS_DEVELOPMENT) {
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
    <title><?= $text["site_name"] . " - " . $text["procedure_title"]; ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <link rel="icon" href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"
          sizes="32x32">
    <link rel="icon" href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"
          sizes="192x192">
    <link rel="apple-touch-icon"
          href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png">
    <link rel="stylesheet" media="all" type="text/css" href="gestoria.css"/>
</head>
<body>
<?php if (!IS_DEVELOPMENT) {
    get_header();
} ?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4"><?= $text["procedure_title"]; ?></h1>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h2><?= $text["my_procedures"]; ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php if (isset($procedure_data) && isset($procedure_data["data"]) && count($procedure_data["data"]) > 0): ?>
                <h4><?= $text["procedure_check"]; ?></h4>
                <table class="table">
                    <caption><?= $text["procedure_list"]; ?></caption>
                    <thead>
                    <tr>
                        <th id="1" scope="col"><?= $text["name"]; ?></th>
                        <th id="2" scope="col"><?= $text["user"] ?></th>
                        <th id="3" scope="col"><?= $text["creation_date"] ?></th>
                        <th id="4" scope="col"><?= $text["update_date"] ?></th>
                        <th id="5" scope="col"><?= $text["status"] ?></th>
                        <th id="6" scope="col"><?= $text["action"] ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($procedure_data["data"] as $row): ?>
                        <tr>
                            <td><?= $row["name"]; ?> </td>
                            <td><?= $row["user"]; ?> </td>
                            <td><?= $row["creation_date"]->format("d-m-Y"); ?> </td>
                            <td><?= $row["update_date"]->format("d-m-Y"); ?> </td>
                            <td><p class="badge badge-secondary"><?= $row["status"]; ?> </p></td>
                            <td><a class="btn btn-success btn-sm"
                                   href="formulario.php?procedure_id=<?= $row["id"]; ?>"><?= $text["open"] ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
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
                <div class="content text-align-center">
                    <div class="alert alert-success">
                        <span><?= $text["no_results"]; ?></span>
                    </div>

                </div>
            <?php endif; ?>
            <div class="text-align-center">
                <a href="tramites.php" class="btn btn-secondary"><?= $text["back"]; ?></a>
            </div>
        </div>
    </div>
</div>
<?php if (!IS_DEVELOPMENT) {
    get_footer();
} ?>
<script>
    $("#wp-submit").addClass("btn btn-success");
</script>
</body>
</html>
