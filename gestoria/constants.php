<?php
session_start();
$_SESSION["email"] = "anyul.rivas@gmail.com";

$text = array(
	"document"            => "Documento",
	"document_type"       => "Tipo de documento",
	"accepted_types"      => "Tipos de archivo aceptados: jpg, png, pdf, zip. Tamaño máximo 4mb",
	"creation_date"       => "Fecha de creación",
	"update_date"         => "Fecha de actualización",
	"user_with_no_orders" => "Su usuario no tiene ningún servicio comprado.",
	"procedure_list"      => "Listado de trámites",
	"send"                => "Enviar",
    "not_logged_in"       => "Usuario no logueado"
);

$document_types = array(
	"passport"             => "Pasaporte",
	"birth_certificate"    => "Acta de nacimiento",
	"marriage_certificate" => "Acta de matrimonio",
	"penal_records"        => "antecedentes penales"
);

$status = array( "Pendiente", "En Proceso", "Cancelado", "Finalizado" );