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

define("STRIPE_API", "pk_test_51HV3x0AlxX0d4JWfw8wkiQCUhaHfSOlq99joORY4akPa2UV7lnAfKboDD6vMhevdMx1YyajljMxG3sdWmOUgWHVo00I6dmlj7c");
define("STRIPE_API_SECRET", "sk_test_51HV3x0AlxX0d4JWf3VRedxULp5mk4CTK9wIRTPls75mWXXhWv2gslmFArxxXzSnut8fTInguT8rZ8JgoF55oxGLj00rhbbYOTc");

define("ORDER_COMPLETED", "Completado");
define("ONEDRIVE_CLIENT_ID", "e2b8f3e6-42cd-44ff-8c87-1a37d9b25c98");
if (strpos($_SERVER["HTTP_HOST"], "localhost") == false) {
    define("ONEDRIVE_REDIRECT_URI", "http://localhost:8080/redirect.php");
} else {
    define("ONEDRIVE_REDIRECT_URI", "https://www.documentofacil.com/redirect.php");
}
define("ONEDRIVE_CLIENT_STATE", "onedrive.client.state");
define("ONEDRIVE_CLIENT_SECRET", "-00V_Lqk1QT1Vv_wUf6ol6rbQeT_Upm8y8");

$text = array(
    "accepted_files" => "Puede Añadir archivos PDF, JPG, BMP, PNG, TIF o GIF de hasta 10MB de tamaño",
    "accepted_types" => "Tipos de archivo aceptados: jpg, png, pdf, zip . Tamaño máximo 10mb",
    "action" => "Acción",
    "add_document" => "Agregar documento",
    "address" => "Dirección",
    "available_services" => "Servicios disponibles",
    "back" => "Atrás",
    "back_to_list" => "Volver al listado",
    "city" => "Ciudad",
    "contact_us" => "Contáctanos",
    "creation_date" => "Fecha de creación",
    "cvc_code" => " y el código CVC",
    "description" => "Una vez que ha ordenado y pagado un servicio, su trámite estará disponible para cargar los documentos necesarios e iniciar el proceso.",
    "document" => "Documento",
    "document_name" => "Nombre del documento",
    "document_number" => "Número de documento",
    "document_type" => "Tipo de documento",
    "documents" => "Documentos",
    "exit" => "Salir",
    "file_too_large" => "El archivo es demasiado grande",
    "first" => "⇤ Primero",
    "form_instructions" => "Documento Fácil revisará los documentos en un plazo máximo de 48 horas.",
    "input_amount" => "Introducir importe",
    "input_amount_help_text" => "Introduce un monto para habilitar la pasarela de pago",
    "input_expiry_date" => "Introduzca la fecha de caducidad en formato MM/AA",
    "instructions" => "Instrucciones",
    "last" => "Último ⇥",
    "lastname" => "Apellido",
    "max_number_reached" => "Número máximo de documentos alcanzado",
    "my_documents" => "Mis documentos",
    "my_procedures" => "Mis trámites",
    "name" => "Nombre",
    "nationality" => "Nacionalidad",
    "next" => "Siguiente &raquo;",
    "no_results" => "Sin resultados",
    "not_logged_in" => "Mi cuenta",
    "online_payment" => "Pago en línea",
    "open" => "Abrir",
    "order_date" => "Fecha del pedido",
    "order_not_completed" => "Para agregar documentos al trámite, el pedido necesita estar completado . ",
    "order_status" => "Estado del pedido",
    "password_lost" => "¿Has perdido tu contraseña?",
    "pay" => "Pagar",
    "payment_concept" => "Concepto de pago",
    "personal_data" => "Datos personales",
    "postal_code" => "Código Postal",
    "previous" => "&laquo; Anterior",
    "procedure_check" => "Comprobar el Status de tu trámite.",
    "procedure_list" => "Listado de trámites",
    "procedure_select" => "¿Qué trámite vas a realizar?",
    "procedure_selection" => "Seleccione un tipo de trámite",
    "procedure_status" => "Estado del trámite",
    "procedure_title" => "Área de Clientes",
    "process_success" => "Operación realizada con éxito",
    "province" => "Provincia",
    "register" => "Registrarse",
    "send" => "Enviar",
    "sent_to_onedrive" => "Enviar a oneDrive",
    "site_name" => "Documento Fácil",
    "status" => "Estatus",
    "telephone" => "Teléfono",
    "update" => "Actualizar",
    "update_date" => "Fecha de actualización",
    "upload_required_documents" => "Subir documentos solicitados",
    "user" => "Usuario"
);
