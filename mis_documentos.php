<?php
include_once "gestoria/constants.php";
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
$document_types = $procedure->get_document_types();
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
            <h2><?= $text["my_documents"]; ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <p><?= $text["upload_required_documents"]; ?></p>
            <form action="" enctype='multipart/form-data' method="post">
                <button id="add_document" class="btn btn-success"
                        type="button"><?= $text["add_document"]; ?></button>
                <div id="procedure_files">
                    <div class="form-row procedure-files">
                        <div class="form-group col-md-8 input-group-file">
                            <label class="form-label" for="file"><?= $text["document"]; ?>:</label>
                            <input name="document[]" type="file"
                                   accept="application/pdf, image/jpeg, image/png,application/zip"
                                   class="form-control-file"/>
                            <small class="form-text text-muted">
                                <?= $text["accepted_files"]; ?>
                            </small>
                        </div>
                        <div class="form-group col-md-4 input-group-document-type">
                            <label for="document_type" class="form-label"><?= $text["document_type"]; ?></label>
                            <select id="document_type" name="document_type[]" class="form-control">
                                <?php foreach ($document_types as $document): ?>
                                    <option value="<?= $document["id"] ?>"><?= $document["type"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="procedure_actions" class="form-row">
                    <div class="form-group col-md-12 text-center">
                        <button type="button" onclick="history.back();"
                                class="btn btn-danger"><?= $text["back"]; ?></button>
                        <button type="submit" name="submit" class="btn btn-primary"><?= $text["send"]; ?></button>
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
    let documentTypes = [<?= $procedure->get_document_types_string(); ?>];
    let formRow = $("<div/>", {class: "form-row procedure-files"});
    let inputFile = $("<input/>", {
        type: "file",
        name: "document[]",
        accept: "application/pdf, image/jpeg, image/png,application/zip",
        class: "form-control-file"
    });
    let helpText = $("<small><?= $text["accepted_files"]; ?></small>", {class: "form-text text-muted"});
    let inputGroupFile = $("<div/>", {class: "form-group col-md-8 input-group-file"})
        .append(`<label class='form-label'><?= $text["document_type"]?>:</label>`, {class: "form-label"})
        .append(inputFile)
        .append(helpText, {class: "form-text text-muted"});
    let select = $("<select/>")
        .addClass("form-control")
        .attr("name", "document_type[]");
    $.each(documentTypes, function (index, value) {
        $(`<option value="${index}">${value}</option/>`).appendTo(select);
    });
    let inputGroupDocumentType = $("<div/>", {class: "form-group col-md-4 input-group-document-type"})
        .append(`<label class='form-label'><?= $text["document_type"]?>: </label>`)
        .append(select);
    formRow.append(inputGroupFile).append(inputGroupDocumentType);
    $("#add_document").click(function () {
        $("#procedure_files").append(formRow.clone());
    });
    $(document).on("change", ".form-control-file", function () {
        console.log("input file changed");
        let fileSize = parseInt(this.files[0].size) / 1024;
        if (fileSize > 10000) {
            alert("<?= $text["file_too_large"];?>");
            this.value = null;
        }
    });
</script>
</body>
</html>