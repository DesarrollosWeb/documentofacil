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
                <div class="form-group col-md-4 input-group-document-type">
                    <label for="document_type" class="form-label"><?= $text["document_type"]; ?></label>
                    <select id="document_type" name="document_type[]" class="form-control">
                        <?php foreach ($document_types as $document): ?>
                            <option value="<?= $document["id"] ?>"><?= $document["type"]; ?></option>
                        <?php endforeach; ?>
                    </select>
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
    $("#wp-submit").addClass("btn btn-success");
</script>
</body>
</html>