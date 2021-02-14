<?php

//region Includes
include_once "gestoria/constants.php";
include_once "gestoria/Procedure.php";
require_once __DIR__ . "/gestoria/vendor/autoload.php";

//endregion

use Krizalys\Onedrive\Client;
use Krizalys\Onedrive\Constant\ConflictBehavior;
use Krizalys\Onedrive\Exception\ConflictException;
use Krizalys\Onedrive\Onedrive;
use Krizalys\Onedrive\Proxy\DriveItemProxy;

if (!isset($text)) {
    $text = [];
}

const CODE = "code";
const PROCEDURE = "procedure";

/**
 * Returns the display name if the document of the name is not present
 * @param array $procedure procedure data
 * @return string a concatenation of the document and user name
 */
function generate_folder_name(array $procedure): string
{
    if (
        empty($procedure["document_type"]) ||
        empty($procedure["document_number"]) ||
        empty($procedure["first_name"]) ||
        empty($procedure["last_name"])
    ) {
        return $procedure["display_name"];
    }
    return $procedure["document_type"] . $procedure["document_number"] . "_" . $procedure["first_name"] . "-" . $procedure["last_name"];
}

/**
 * Creates the user folder in the selected path or the root folder otherwise
 * @param Client $client onedrive client
 * @param string $folder_id selected folder
 * @param string $folder_name folder name to create
 * @return DriveItemProxy|null the created folder, null in case of exception
 */
function create_user_folder(Client $client, string $folder_id, string $folder_name): ?DriveItemProxy
{
    try {
        $folder = $client->getMyDrive()->getDriveItemById($folder_id);
        $folder = $folder->createFolder($folder_name);
    } catch (Exception $e) {
        try {
            $folder = $client->getMyDrive()->getRoot()->createFolder($folder_name);
            $folder = $folder->createFolder($folder_name);
        } catch (Exception $e) {
            return null;
        }
    }
    return $folder;
}

/**
 * @param array $files
 * @param DriveItemProxy|null $folder
 * @return array
 */
function copy_files(array $files, ?DriveItemProxy $folder): array
{
    $items = [];
    if ($folder != null) {
        try {
            foreach ($files as $file) {
                $segments = explode("/", $file);
                $filename = $segments[count($segments) - 1];
                $items[$filename] = $folder->upload($filename, fopen($file, "r"), ["conflictBehavior" => ConflictBehavior::REPLACE]);
            }
        } catch (ConflictException | Exception $e) {
            krumo($e);
        }
    }
    return $items;
}

if (array_key_exists("submit", $_POST)) {
    $folder_id = $_POST["folder"];
    $procedure_id = filter_var($_POST["procedure_id"], FILTER_SANITIZE_NUMBER_INT);
    $procedure = new Procedure("");
    $order_and_procedure = $procedure->get_order_and_procedure($procedure_id);
    $folder_name = generate_folder_name($order_and_procedure[PROCEDURE]);
    $user_files = $procedure->get_user_files($order_and_procedure[PROCEDURE]["email"]);
    $files_path = array_map(fn($element) => $element["file_path"], $user_files["data"]);
    $client = Onedrive::client(ONEDRIVE_CLIENT_ID, ["state" => $_SESSION[ONEDRIVE_CLIENT_STATE]]);
    $user_folder = create_user_folder($client, $folder_id, $folder_name);
    $items = copy_files($files_path, $user_folder);
} else {
    if (!array_key_exists(CODE, $_GET)) {
        throw new Exception("undefined code in request");
    }

    $client = Onedrive::client(ONEDRIVE_CLIENT_ID,
        [
            "state" => $_SESSION[ONEDRIVE_CLIENT_STATE]
        ]
    );
    try {
        $client->obtainAccessToken(ONEDRIVE_CLIENT_SECRET, $_GET[CODE]);
        $_SESSION[ONEDRIVE_CLIENT_STATE] = $client->getState();
        $_SESSION["ACCESS_TOKEN"] = $_GET[CODE];
        $children = $client->getMyDrive()->getRoot()->getChildren();
    } catch (Exception $e) {
        krumo($e);
        echo $e->getMessage();
        krumo($_SESSION);
    }
}

if (array_key_exists("error", $_GET)) {
    echo '<strong>' . $_GET["error"] . '</strong><p>' . $_GET["error_description"] . '</p>';
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
<div class="container">
    <div class="row">
        <div class="col">
            <?php if (isset($items)): ?>
                <div class="text-align-center">
                    <div class="alert alert-success">
                        <?= $text["process_success"]; ?>.
                    </div>
                    <a href="tramites.php" class="btn btn-secondary"><?= $text["back_to_list"]; ?></a>
                </div>
            <?php else: ?>
                <form action="" method="post">
                    <input type="hidden" name="procedure_id" value="<?= $_GET["state"]; ?>">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <?php if (isset($children)) : ?>
                                <label for="folder" class="form-label">Selecciona una carpeta</label>
                                <select name="folder" id="folder" class="form-control">
                                    <?php foreach ($children as $child): ?>
                                        <?php if (is_object($child->folder)) : ?>
                                            <option value="<?= $child->id ?>"><?= $child->name . " (" . $child->folder->childCount . ")"; ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                                <button id="submit" type="submit" name="submit"
                                        class="btn btn-success"><?= $text["sent_to_onedrive"]; ?></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
