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
    //$user = get_userdata(2);
    $_SESSION["email"] = "anyulled@gmail.com";
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
    <?php if (isset($user) && $user->ID == 0): ?>
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
                    <li class="list-group-item"><a href="datos_personales.php"><?= $text["personal_data"]; ?></a></li>
                    <li class="list-group-item"><a href="pago_en_linea.php"><?= $text["online_payment"]; ?></a></li>
                    <li class="list-group-item"><a href="mis_tramites.php"><?= $text["my_procedures"]; ?></a></li>
                    <li class="list-group-item"><a href="mis_documentos.php"><?= $text["my_documents"]; ?></a></li>
                    <li class="list-group-item"><a
                                target="_blank"
                                rel="noopener noreferrer"
                                href="mailto:info@documentofacil.com?subject=Solicitud%20Info"><?= $text["contact_us"]; ?></a>
                    </li>
                    <li class="list-group-item">
                        <a href="https://www.documentofacil.com/wp-login.php?action=logout"><?= $text["exit"]; ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-9"></div>
        </div>
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
