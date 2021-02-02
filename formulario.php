<?php
//if (!IS_DEVELOPMENT) {
include_once "wp-load.php";
//}
//region Includes
include_once "gestoria/vendor/autoload.php";
include_once "gestoria/constants.php";
include_once "gestoria/Procedure.php";
//endregion

$procedure = new Procedure($_SESSION["email"]);

$document_types = $procedure->get_document_types();
$procedure_id = $_GET["procedure_id"];
if (isset($_POST["submit"])) {
    $result = $procedure->process($_POST, $_FILES);
}
$procedure_data = $procedure->get_order_and_procedure($procedure_id);
try {
    $user = $procedure->get_user_type($_SESSION["email"]);
    if (IS_DEVELOPMENT) {
        krumo($user);
    }
} catch (Exception $e) {
    echo "Usuario no encontrado:" . $e->getMessage();
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" media="all" type="text/css"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <title><?= $text["site_name"] . " - " . $text["procedure_title"]; ?></title>
    <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
    <link rel="icon" href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"
          sizes="32x32"/>
    <link rel="icon" href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"
          sizes="192x192"/>
    <link rel="apple-touch-icon"
          href="https://www.documentofacil.com/wp-content/uploads/2020/12/LOGO-DODUMENTO-LETRAS-EN-NEGRO.png"/>
    <link rel="stylesheet" media="all" type="text/css" href="gestoria.css"/>
</head>
<body>
<?php if (!IS_DEVELOPMENT) {
    get_header();
} ?>
<div class="container">
    <div class="card" id="procedure_info">
        <div class="card-body">
            <h3 class="card-title"><?= $procedure_data["procedure"]["name"]; ?></h3>
            <h4 class="card-subtitle mb-2 text-muted"><?= $text["procedure_status"]; ?>
                : <?= $procedure_data["procedure"]["procedure_status"]; ?></h4>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong><?= $text["order_date"]; ?></strong> <?= $procedure_data["procedure"]["creation_date"]->format("d-m-Y"); ?>
                </li>
                <li class="list-group-item">
                    <strong><?= $text["user"]; ?></strong>: <?= $procedure_data["procedure"]["user"]; ?></li>
                <li class="list-group-item">
                    <strong>Email</strong>: <?= $procedure_data["procedure"]["email"]; ?></li>
                <li class="list-group-item">
                    <strong><?= $text["creation_date"]; ?>
                        :</strong> <?= $procedure_data["procedure"]["creation_date"]->format("d-m-Y"); ?></li>
                <li class="list-group-item">
                    <strong><?= $text["update_date"]; ?>
                        :</strong> <?= $procedure_data["procedure"]["update_date"]->format("d-m-Y"); ?> </li>
            </ul>
        </div>
    </div>
    <?php if (isset($result)): ?>
        <div class="alert alert-info">
            <?= $text["process_success"]; ?>.
        </div>
        <a href="tramites.php" class="btn btn-secondary"><?= $text["back_to_list"]; ?></a>
    <?php else: ?>
        <div class="row">
            <div class="col">
                <div id="procedure_files">
                    <h4><?= $text["documents"]; ?></h4>
                    <ul class="list-group list-group-horizontal">
                        <?php foreach ($procedure_data["files"] as $file): ?>
                            <li class="list-group-item">
                                <a href="<?= "https://" . $_SERVER["SERVER_NAME"] . "/" . $file["file_path"]; ?>"
                                   target="_blank"><?= (!empty($file["document_name"])) ? $file["type"] . " - " . $file["document_name"] : $file["type"]; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <form action="" enctype='multipart/form-data' method="post">
            <input type="hidden" name="procedure_id" id="procedure_id"
                   value="<?= $procedure_data["procedure"]["procedure_id"]; ?>"/>
            <input type="hidden" name="procedure_name" value="<?= $procedure_data["procedure"]["name"] ?>"/>
            <input type="hidden" name="user" value="<?= $procedure_data["procedure"]["user"]; ?>"/>
            <div id="procedure_status" class="form-row">
                <?php if (isset($user["rol"]["administrator"])): ?>
                    <div class="form-group col-md-12">
                        <label for="procedure_status" class="form-label"><?= $text["status"]; ?></label>
                        <select name="procedure_status" id="procedure_status" class="form-control">
                            <?php foreach ($procedure->get_procedure_status() as $status): ?>
                                <option <?php echo (strcasecmp($procedure_data["procedure"]["procedure_status"], $status["status"]) == 0) ? "selected" : ""; ?>
                                        value="<?= $status["id"]; ?>"><?= $status["status"]; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (isset($user["rol"]["administrator"])): ?>
                <div id="procedure_actions" class="form-row">
                    <div class="form-group col-md-12 text-center">
                        <button type="button" onclick="history.back();"
                                class="btn btn-secondary"><?= $text["back"]; ?></button>
                        <button type="submit" name="submit" class="btn btn-primary"><?= $text["send"]; ?></button>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    <?php endif; ?>
</div>
<?php if (!IS_DEVELOPMENT) {
    get_footer();
} ?>
</body>
</html>
