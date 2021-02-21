<?php
include_once "gestoria/constants.php";
//region ini settings
ini_set("upload_max_filesize", '10M');
ini_set("post_max_size", '100M');
ini_set("memory_limit", '512M');
//endregion

if (!IS_DEVELOPMENT) {
    include_once "wp-load.php";
}
//region Includes
include_once "gestoria/vendor/autoload.php";
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
if (isset($_POST["submit"])) {
    $result = $procedure->process($_POST, $_FILES);
    if (IS_DEVELOPMENT) {
        krumo($result);
    }
}
$document_types = $procedure->get_document_types();
$user_files = $procedure->get_user_files();
try {
    $user_info = $procedure->get_user_type("");
} catch (Exception $e) {
    die($e->getMessage());
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
    <div class="jumbotron">
        <h1 class="display-4"><?= $text["procedure_title"]; ?></h1>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h1><?= $text["my_documents"]; ?></h1>
        </div>
    </div>
    <?php if (isset($result)): ?>
        <div class="alert alert-success">
            <?= $text["process_success"]; ?>.
        </div>
    <?php endif; ?>

    <div class="row" id="my-documents">
        <div class="col-sm-12">
            <?php if (count($user_files["data"]) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($user_files["data"] as $document): ?>
                        <li class="list-group-item"><i class="far fa-file"></i>
                            <a href="<?= "https://" . $_SERVER["SERVER_NAME"] . "/" . $document["file_path"]; ?>"
                               target="_blank"><?= (!empty($document["document_name"])) ? $document["type"] . " - " . $document["document_name"] : $document["type"]; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <hr/>
    <div class="row" id="document-form">
        <div class="col-sm-12">
            <h2><?= $text["upload_required_documents"]; ?></h2>
            <form action="" enctype='multipart/form-data' method="post">
                <input type="hidden" name="user" value="<?= $user_info["display_name"]; ?>"/>
                <button id="add_document" class="btn btn-success"
                        type="button"><?= $text["add_document"]; ?></button>
                <div id="procedure_files">
                    <div class="form-row procedure-files">
                        <div class="form-group col input-group-file">
                            <label class="form-label" for="file"><?= $text["document"]; ?>:</label>
                            <input name="document[]" type="file"
                                   accept="application/pdf, image/jpeg, image/png,application/zip"
                                   class="form-control-file"/>
                            <small class="form-text text-muted">
                                <?= $text["accepted_files"]; ?>
                            </small>
                        </div>
                        <div class="form-group col input-group-document-type">
                            <label for="document_type" class="form-label"><?= $text["document_type"]; ?></label>
                            <select id="document_type" name="document_type[]" class="form-control">
                                <?php foreach ($document_types as $document): ?>
                                    <option value="<?= $document["id"] ?>"><?= $document["type"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col input-group-document-name d-none">
                            <label for="document_name" class="form-label"><?= $text["document_name"]; ?></label>
                            <input id="document_name" type="text" name="document_name[]" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div id="procedure_actions" class="form-row">
                    <div class="form-group col-md-12 text-center">
                        <button type="button" onclick="history.back();"
                                class="btn btn-secondary"><?= $text["back"]; ?></button>
                        <button type="submit" name="submit" class="btn btn-success"><?= $text["send"]; ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if (!IS_DEVELOPMENT) {
    get_footer();
} ?>
<script type="application/javascript">
    const maximumFilesPerUpload = <?= ini_get("max_file_uploads"); ?>;
    let documentCounter = 0;
    const documentTypes = [<?= $procedure->get_document_types_string(); ?>];
    const formRow = $("<div/>", {class: "form-row procedure-files"});
    const inputFile = $("<input/>", {
        type: "file",
        name: "document[]",
        accept: "application/pdf, image/jpeg, image/png,application/zip",
        class: "form-control-file"
    });
    const documentNameInput = $("<input/>", {
        type: "text",
        name: "document_name[]",
        class: "form-control"
    });
    const OtherDocuments = "8";
    let helpText = $("<small><?= $text["accepted_files"]; ?></small>", {class: "form-text text-muted"});
    let inputGroupFile = $("<div/>", {class: "form-group col input-group-file"})
        .append(`<label class='form-label'><?= $text["document"]?>:</label>`, {class: "form-label"})
        .append(inputFile)
        .append(helpText, {class: "form-text text-muted"});
    let select = $("<select/>")
        .addClass("form-control")
        .attr("name", "document_type[]");
    $.each(documentTypes, (index, value) => {
        $(`<option value="${index}">${value}</option/>`).appendTo(select);
    });
    let inputGroupDocumentType = $("<div/>", {class: "form-group col input-group-document-type"})
        .append(`<label class='form-label'><?= $text["document_name"]?>: </label>`)
        .append(select);
    const inputGroupDocumentName = $("<div/>", {class: "form-group col input-group-type-name d-none"})
        .append(`<label class="form-label"><?=$text["document_name"];?></label>`)
        .append(documentNameInput);
    formRow.append(inputGroupFile).append(inputGroupDocumentType).append(inputGroupDocumentName);
    $("#add_document").click(() => {
        if (documentCounter < maximumFilesPerUpload) {
            $("#procedure_files").append(formRow.clone());
            documentCounter++;
        } else {
            alert("<?= $text["max_number_reached"];?>");
        }
    });
    $(document).on("change", ".form-control-file", function () {
        let fileSize = parseInt(this.files[0].size) / 1024;
        if (fileSize > 10000) {
            alert("<?= $text["file_too_large"];?>");
            this.value = null;
        }
    });
    $(document).on("change", "select[name='document_type[]']", function () {
        if ($(this).val() === OtherDocuments) {
            $(this).parent().next(".form-group").removeClass("d-none");
        } else {
            $(this).parent().next(".form-group").addClass("d-none");
        }
    });
</script>
</body>
</html>