<?php

//region Includes
include_once "gestoria/vendor/autoload.php";
include_once "gestoria/constants.php";
include_once "gestoria/Procedure.php";
include_once 'gestoria/vendor/kktsvetkov/krumo/class.krumo.php';
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
    krumo($user);
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
    <title>:: Área de Tramites :: </title>
    <link rel="stylesheet" media="all" type="text/css"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
            integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?= $procedure_data["procedure"]["name"]; ?></h5>
            <h6 class="card-subtitle mb-2 text-muted"><?= $text["order_status"]; ?>
                : <?= $procedure_data["procedure"]["order_status"]; ?></h6>
            <h6 class="card-subtitle mb-2 text-muted"><?= $text["procedure_status"]; ?>
                : <?= $procedure_data["procedure"]["procedure_status"]; ?></h6>
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
                        :</strong> <?= $procedure_data["procedure"]["procedure_creation_date"]->format("d-m-Y"); ?></li>
                <li class="list-group-item">
                    <strong><?= $text["update_date"]; ?>
                        :</strong> <?= $procedure_data["procedure"]["procedure_update_date"]->format("d-m-Y"); ?> </li>
            </ul>
        </div>
    </div>
    <?php if (isset($result)): ?>
        <div class="alert alert-info">
            <?= $text["process_success"]; ?>.
        </div>
    <?php else: ?>
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
                            <?php foreach ($procedure->procedure_statuses as $key => $value): ?>
                                <option <?php echo (strcasecmp($procedure_data["procedure"]["procedure_status"], $value) == 0) ? "selected" : ""; ?>
                                        value="<?= $key; ?>"><?= $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <div id="procedure_files">
                <?php foreach ($procedure_data["files"] as $file): ?>
                    <div class="form-row procedure-files">
                        <?php if ($file["file_path"] != "/"): ?>
                            <div class="form-group col-md-12">
                                <input type="hidden" name="procedure_file_id" value="<?= $file["id"]; ?>">
                                <a href="<?= $file["file_path"]; ?>" target="_blank"><?= $file["type"]; ?></a>
                            </div>
                        <?php else: ?>
                            <div class="form-group col-md-8 input-group-file">
                                <label class="form-label" for="file"><?= $text["document"]; ?>:</label>
                                <input id="file" name="document[]" type="file"
                                       accept="application/pdf, image/jpeg, image/png,application/zip"
                                       class="form-control-file"/>
                            </div>
                            <div class="form-group col-md-4 input-group-document-type">
                                <label for="document_type" class="form-label"><?= $text["document_type"]; ?></label>
                                <select id="document_type" name="document_type[]" class="form-control">
                                    <?php foreach ($document_types as $key => $value): ?>
                                        <option value="<?= $key ?>"><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($procedure_data["procedure"]["order_status"] == ORDER_COMPLETED): ?>
                <button id="add_document" class="btn btn-info" type="button"><?= $text["add_document"]; ?></button>
            <?php else: ?>
                <div class="alert alert-info"><?= $text["order_not_completed"]; ?></div>
            <?php endif; ?>
            <div id="procedure_actions" class="form-row">
                <div class="form-group col-md-12 text-center">
                    <button type="button" onclick="history.back();"
                            class="btn btn-danger"><?= $text["back"]; ?></button>
                    <button type="submit" name="submit" class="btn btn-primary"><?= $text["send"]; ?></button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
<script type="application/javascript">
    let documentTypes = ["pasaporte",
        "Acta de nacimiento",
        "acta de matrimonio",
        "antecedentes penales",
        "carta de solteria",
        "declaracion de edictos",
        "homologaciones",
        "legalizaciones",
        "apostilla de la haya"];
    let formRow = $("<div/>", {class: "form-row procedure-files"});
    let inputFile = $("<input/>", {
        type: "file",
        name: "document[]",
        accept: "application/pdf, image/jpeg, image/png,application/zip",
        class: "form-control-file"
    });
    let inputGroupFile = $("<div/>", {class: "form-group col-md-8 input-group-file"})
        .append(`<label class='form-label'>Documento:</label>`, {class: "form-label"})
        .append(inputFile);
    let select = $("<select/>")
        .addClass("form-control")
        .attr("name", "document_type[]");
    $.each(documentTypes, function (index, value) {
        $(`<option value="${index}">${value}</option/>`).appendTo(select);
    });
    let inputGroupDocumentType = $("<div/>", {class: "form-group col-md-4 input-group-document-type"})
        .append(`<label class='form-label'>Tipo de documento:</label>`)
        .append(select);
    formRow.append(inputGroupFile).append(inputGroupDocumentType);
    $("#add_document").click(function () {
        $("#procedure_files").append(formRow.clone());
    });
</script>
</body>
</html>
