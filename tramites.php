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
$current_page = 1;
$items_per_page = 5;
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
    <style media="all" type="text/css">
        #user_login {
            width: 100%;
            padding: 7px 10px;
            border: 1px solid #cecece;
        }

        .container .jumbotron {
            background: rgb(163, 192, 8);
            background: linear-gradient(90deg, rgba(163, 192, 8, 1) 0%, rgba(23, 107, 5, 1) 100%);
        }

        .container .jumbotron h1 {
            color: white;
            text-shadow: 1px 1px 3px black;
        }
    </style>
    <title><?= $text["site_name"] . " - " . $text["procedure_title"]; ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <link rel="icon" href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"
          sizes="32x32">
    <link rel="icon" href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"
          sizes="192x192">
    <link rel="apple-touch-icon"
          href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png">
</head>
<body>
<?php if (!IS_DEVELOPMENT) {
    get_header();
} ?>
<div class="container">
    <div class="jumbotron">
        <h1 class="display-4"><?= $text["procedure_title"]; ?></h1>
    </div>
    <?php if (isset($procedure_data) && count($procedure_data["data"]) > 0): ?>
        <h4><?= $text["procedure_check"]; ?></h4>
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
                    <td><a class="btn btn-success btn-sm"
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
        <?php if (Procedure::user_is_null($user)): ?>
            <div class="alert">
                <h4><?= $text["not_logged_in"]; ?></h4>
                <?php wp_login_form(); ?>
                <a class="btn btn-secondary"
                   href="https://www.documentofacil.com/index.php/register/"> <?= $text["register"]; ?></a>
            </div>
            <div class="alert alert-secondary" role="alert">
                <a href="<?php echo wp_lostpassword_url('tramites.php'); ?>"><?= $text["password_lost"]; ?></a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-sm-3">
                    <ul class="list-group">
                        <li class="list-group-item">Datos Personales</li>
                        <li class="list-group-item">Pago en línea</li>
                        <li class="list-group-item">Mis trámites</li>
                        <li class="list-group-item">Contáctanos</li>
                        <li class="list-group-item">Salir</li>
                    </ul>
                </div>
                <div class="col-sm-9"></div>

            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>
<?php if (!IS_DEVELOPMENT) {
    get_footer();
} ?>
<script type="application/javascript">
    $("#wp-submit").addClass("btn btn-success");
</script>
</body>
</html>
