<?php
//region Includes
include_once "gestoria/constants.php";
//if (!IS_DEVELOPMENT) {
    include_once "wp-load.php";
//}
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
$document_types = Procedure::get_identification__types();
$fields = [
    "first_name",
    "last_name",
    "document_type2",
    "document_number",
    "billing_address_1",
    "billing_city",
    "billing_postcode",
    "billing_state",
    "billing_phone",
    "billing_email",
    "nationality"
];
$states = WC()->countries->get_states("ES");

if (isset($_POST["submit"]) && isset($user)) {
    $result = $procedure->update_user_info($user, $_POST);
}
if (isset($user) && (!isset($user_metadata["billing_email"][0]) || empty($user_metadata["billing_email"][0]))) {
    $user_metadata["billing_email"][0] = $user->user_email;
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
    <title><?= $text["site_name"] . " - " . $text["personal_data"]; ?></title>
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
            <h1><?= $text["personal_data"]; ?></h1>
        </div>
    </div>
    <?php if (isset($result)): ?>
        <div class="row">
            <div class="col">
                <div class="alert alert-info">
                    <?= $text["process_success"]; ?>.
                </div>
                <a href="tramites.php" class="btn btn-secondary"><?= $text["back_to_list"]; ?></a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php if (!IS_DEVELOPMENT) {
    get_footer();
} ?>
</body>
</html>
