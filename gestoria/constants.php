<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
define("IS_DEBUG", true);
define('WP_DEBUG', true);
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'DO278_WP');

/** MySQL database username */
define('DB_USER', 'DO278_WP');

/** MySQL database password */
define('DB_PASSWORD', 'G8;yV2;qX1(a');

/** MySQL hostname */
define('DB_HOST', 'documentofacil.com');
//define( 'DB_HOST', 'MacBook-Pro.local' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define("ORDER_COMPLETED", "Completado");

define("ONEDRIVE_CLIENT_ID", "dd2de099-4803-452b-a5fa-63d2ad67c47f");
define("ONEDRIVE_REDIRECT_URI", "http://localhost:8080/redirect.php");
define("ONEDRIVE_CLIENT_STATE", "onedrive.client.state");
define("ONEDRIVE_CLIENT_SECRET", "v2IhAMhV1uln~9~p_TWJ5xc_4ql.X7h3fa");

$text = array(
    "procedure_title" => "Área de Trámites",
    "document" => "Documento",
    "document_type" => "Tipo de documento",
    "accepted_types" => "Tipos de archivo aceptados: jpg, png, pdf, zip . Tamaño máximo 4mb",
    "creation_date" => "Fecha de creación",
    "update_date" => "Fecha de actualización",
    "user_with_no_orders" => "Su usuario no tiene ningún servicio comprado . ",
    "procedure_list" => "Listado de trámites",
    "send" => "Enviar",
    "not_logged_in" => "Usuario no logueado",
    "order_date" => "Fecha del pedido",
    "open" => "Abrir",
    "name" => "nombre",
    "user" => "Usuario",
    "status" => "estatus",
    "order_status" => "Estado del pedido",
    "procedure_status" => "Estado del trámite",
    "action" => "acción",
    "first" => "⇤ Primero",
    "previous" => "&laquo; Anterior",
    "next" => "Siguiente &raquo;",
    "last" => "Último ⇥",
    "back" => "Atrás",
    "process_success" => "Operación realizada con éxito",
    "add_document" => "Agregar documento",
    "order_not_completed" => "Para agregar documentos al trámite, el pedido necesita estar completado . "
);
