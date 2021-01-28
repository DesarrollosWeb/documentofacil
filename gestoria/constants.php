<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);

define("IS_DEVELOPMENT", true);
if (IS_DEVELOPMENT) {
// ** MySQL settings - You can get this info from your web host ** //
    /** The name of the database for WordPress */
    define('DB_NAME', 'DO278_WP');
    /** MySQL database username */
    define('DB_USER', 'DO278_WP');

    /** MySQL database password */
    define('DB_PASSWORD', 'G8;yV2;qX1(a');

    /** MySQL hostname */
    define('DB_HOST', 'documentofacil.com');

    /** Database Charset to use in creating database tables. */
    define('DB_CHARSET', 'utf8mb4');

    /** The Database Collate type. Don't change this if in doubt. */
    define('DB_COLLATE', '');

}

//define("STRIPE_API", "pk_live_51HV3x0AlxX0d4JWfQUlODOXE9eIZQ5jhFhWrTfs0Rr6Gu64MAJYTQRxJ5WyUh4yRRhxZYDExbdXbNuw0fQq7X6MN00xPzwgWhO");
//define("STRIPE_API_SECRET", "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");

define("STRIPE_API", "pk_test_51HV3x0AlxX0d4JWfw8wkiQCUhaHfSOlq99joORY4akPa2UV7lnAfKboDD6vMhevdMx1YyajljMxG3sdWmOUgWHVo00I6dmlj7c");
define("STRIPE_API_SECRET", "sk_test_51HV3x0AlxX0d4JWf3VRedxULp5mk4CTK9wIRTPls75mWXXhWv2gslmFArxxXzSnut8fTInguT8rZ8JgoF55oxGLj00rhbbYOTc");

define("ORDER_COMPLETED", "Completado");
define("ONEDRIVE_CLIENT_ID", "dd2de099-4803-452b-a5fa-63d2ad67c47f");
define("ONEDRIVE_REDIRECT_URI", "http://localhost:8080/redirect.php");
define("ONEDRIVE_CLIENT_STATE", "onedrive.client.state");
define("ONEDRIVE_CLIENT_SECRET", "v2IhAMhV1uln~9~p_TWJ5xc_4ql.X7h3fa");

$text = array(
    "procedure_title" => "Área de Clientes",
    "document" => "Documento",
    "document_type" => "Tipo de documento",
    "document_name" => "Nombre del documento",
    "accepted_types" => "Tipos de archivo aceptados: jpg, png, pdf, zip . Tamaño máximo 10mb",
    "creation_date" => "Fecha de creación",
    "update_date" => "Fecha de actualización",
    "no_results" => "Sin resultados",
    "procedure_list" => "Listado de trámites",
    "send" => "Enviar",
    "contact_us" => "Contáctanos",
    "not_logged_in" => "Mi cuenta",
    "documents" => "Documentos",
    "order_date" => "Fecha del pedido",
    "my_procedures" => "Mis trámites",
    "my_documents" => "Mis documentos",
    "max_number_reached" => "Número máximo de documentos alcanzado",
    "open" => "Abrir",
    "name" => "Nombre",
    "user" => "Usuario",
    "status" => "Estatus",
    "password_lost" => "¿Has perdido tu contraseña?",
    "personal_data" => "Datos personales",
    "order_status" => "Estado del pedido",
    "file_too_large" => "El archivo es demasiado grande",
    "procedure_status" => "Estado del trámite",
    "action" => "Acción",
    "first" => "⇤ Primero",
    "previous" => "&laquo; Anterior",
    "next" => "Siguiente &raquo;",
    "last" => "Último ⇥",
    "back" => "Atrás",
    "site_name" => "Documento Fácil",
    "exit" => "Salir",
    "upload_required_documents" => "Subir documentos solicitados",
    "register" => "Registrarse",
    "procedure_check" => "Comprobar el Status de tu trámite.",
    "procedure_select" => "¿Qué trámite vas a realizar?",
    "back_to_list" => "Volver al listado",
    "process_success" => "Operación realizada con éxito",
    "add_document" => "Agregar documento",
    "order_not_completed" => "Para agregar documentos al trámite, el pedido necesita estar completado . ",
    "instructions" => "Instrucciones",
    "form_instructions" => "Documento Fácil revisará los documentos en un plazo máximo de 48 horas.",
    "accepted_files" => "Puede Añadir archivos PDF, JPG, BMP, PNG, TIF o GIF de hasta 10MB de tamaño",
    "available_services" => "Servicios disponibles",
    "description" => "Una vez que ha ordenado y pagado un servicio, su trámite estará disponible para cargar los documentos
                necesarios e iniciar el proceso."
);
