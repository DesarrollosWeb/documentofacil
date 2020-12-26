<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$query = "select proc.id, proc.creation_date, u.name, u.email, ps.status, count(ps.id) as ficheros
from `procedure` proc
         inner join user u on proc.user_id = u.id
         inner join procedure_status ps on proc.status_id = ps.id
         left outer join procedure_files pf on proc.id = pf.procedure_id
group by proc.id, proc.creation_date, u.name, u.email, ps.status";

$dsn = "mysql:host=MacBook-Pro.local;dbname=gestoria;charset=utf8mb4;port=3306";
try {
    $connection = new PDO($dsn, 'root', 1234);
    $statement = $connection->query($query);
    $data = $statement->fetchAll();

    foreach ($data as $row) {
        var_dump($row);
    }
} catch (Exception $e) {
    print "Error:";
    var_dump($e);
}
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Procedure Board</title>
    </head>
    <body>
    <?php phpinfo();?>

    </body>
    </html>
    <h1>Procedure Board</h1>

<?php
