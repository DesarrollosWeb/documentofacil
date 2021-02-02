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
    $user = get_userdata(2);
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
$user_metadata = get_user_meta($user->ID, "", false);
if (isset($user)) {
    if (!isset($user_metadata["billing_email"][0]) || empty($user_metadata["billing_email"][0])) {
        $user_metadata["billing_email"][0] = $user->user_email;
    }
    foreach ($fields as $field) {
        if (!isset($user_metadata[$field])) {
            $user_metadata[$field][0] = "";
        }
    }
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
    <div class="row">
        <div class="col">
            <form method="post" action="">
                <div class="form-group row">
                    <label for="first_name" class="col-4 col-form-label">Nombre</label>
                    <div class="col-8">
                        <input id="first_name" name="first_name" type="text" required="required" class="form-control"
                               value="<?= $user_metadata["first_name"][0]; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="last_name" class="col-4 col-form-label">Apellido</label>
                    <div class="col-8">
                        <input id="last_name" name="last_name" type="text" required="required" class="form-control"
                               value="<?= $user_metadata["last_name"][0]; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="document_type2" class="col-4 col-form-label">Tipo documento</label>
                    <div class="col-8">
                        <select id="document_type2" name="document_type2" required="required" class="custom-select">
                            <?php foreach ($document_types as $key => $value): ?>
                                <option value="<?= $key; ?>" <?= ($key == $user_metadata["document_type2"][0]) ? "selected" : ""; ?>><?= $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="document_number" class="col-4 col-form-label">Numero documento</label>
                    <div class="col-8">
                        <input id="document_number" name="document_number" type="text" required="required"
                               class="form-control"
                               value="<?= $user_metadata["document_number"][0]; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="billing_address_1" class="col-4 col-form-label">Direccion</label>
                    <div class="col-8">
                        <input id="billing_address_1" name="billing_address_1" type="text" class="form-control"
                               value="<?= $user_metadata["billing_address_1"][0]; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="billing_city" class="col-4 col-form-label">Ciudad</label>
                    <div class="col-8">
                        <input id="billing_city" name="billing_city" type="text" class="form-control"
                               value="<?= $user_metadata["billing_city"][0]; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="billing_state" class="col-4 col-form-label">Provincia</label>
                    <div class="col-8">
                        <select name="billing_state" id="billing_state" class="custom-select">
                            <?php foreach ($states as $key => $value): ?>
                                <option value="<?= $key ?>" <?= ($key == $user_metadata["billing_state"][0]) ? "selected" : ""; ?> ><?= $value; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="billing_postcode" class="col-4 col-form-label">Código Postal</label>
                    <div class="col-8">
                        <input id="billing_postcode" name="billing_postcode" type="text" class="form-control"
                               maxlength="5"
                               value="<?= $user_metadata["billing_postcode"][0]; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="billing_phone" class="col-4 col-form-label">Teléfono</label>
                    <div class="col-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">+34</div>
                            </div>
                            <input id="billing_phone" name="billing_phone" type="text" class="form-control"
                                   maxlength="9"
                                   value="<?= $user_metadata["billing_phone"][0]; ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="billing_email" class="col-4 col-form-label">Email</label>
                    <div class="col-8">
                        <input id="billing_email" name="billing_email" type="email" class="form-control"
                               required="required"
                               value="<?= $user_metadata["billing_email"][0]; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nationality" class="col-4 col-form-label">Nacionalidad</label>
                    <div class="col-8">
                        <input id="nationality" name="nationality" type="text" class="form-control"
                               value="<?= $user_metadata["nationality"][0]; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="offset-4 col-8">
                        <button name="submit" type="submit" class="btn btn-success">Enviar</button>
                        <a href="tramites.php" class="btn btn-secondary"><?= $text["back"]; ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if (!IS_DEVELOPMENT) {
    get_footer();
} ?>
</body>
</html>
