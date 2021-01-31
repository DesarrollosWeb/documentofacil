<?php

//region Includes
include_once 'vendor/autoload.php';
include_once "Db.php";

//endregion

class Procedure
{
    private string $email;

    private Db $db;

    private bool $is_admin = false;

    public static array $order_statuses = array(
        'wc-pending' => 'Pago Pendiente',
        'wc-processing' => 'Procesando',
        'wc-on-hold' => 'En Espera',
        'wc-completed' => 'Completado',
        'wc-cancelled' => 'Cancelado',
        'wc-refunded' => 'Reembolsado',
        'wc-failed' => 'Fallido',
        'trash' => "Eliminado"
    );

    public array $procedure_statuses = array(
        0 => "Pendiente",
        1 => "En Proceso",
        2 => "Finalizado",
        3 => "Anulado");

    public function __construct(string $email)
    {
        $this->email = $email;
        $this->db = DB::getInstance();
    }

    private string $admin_query = "select p.id,
        creation_date,
       t.name as name,
       s.status,
       u.display_name as user,
       u.user_email
from wp_procedure p
    inner join wp_procedure_status s on p.status_id=s.id
    inner join wp_procedure_type t on t.id = p.procedure_type
    inner join wpsw_users u on u.ID = p.user_id 
";

    private string $client_query = "select p.id,
        creation_date,
       t.name as name,
       s.status,
       u.display_name as user,
       u.user_email
from wp_procedure p
    inner join wp_procedure_status s on p.status_id=s.id
    inner join wp_procedure_type t on t.id = p.procedure_type
    inner join wpsw_users u on u.ID = p.user_id 
where u.user_email=:email";

    /**
     * Parses a date from a string format into a DateTime with UTC timezone.
     *
     * @param string $creation_date creation date from database. Format Y-m-d H:i:s
     *
     * @return DateTime|false
     */
    private static function createDateTimeFromString(string $creation_date)
    {
        return DateTime::createFromFormat("Y-m-d H:i:s", $creation_date, new DateTimeZone('UTC'));
    }

    /**
     * Get information about the user and its role
     * @param string $email
     * @return array
     * @throws Exception
     */
    public function get_user_type(string $email): array
    {
        if (empty($email)) {
            $email = $this->email;
        }
        $user_type = $this->db->get_query("select user_email, display_name, meta_value as 'rol' 
        from wpsw_users
        inner join wpsw_usermeta wu on wpsw_users.ID = wu.user_id and wu.meta_key = 'wpsw_capabilities'
        where user_email = :email", ["email" => $email]);
        if (count($user_type["data"]) > 0) {
            for ($i = 0; $i < count($user_type["data"]); $i++) {
                $user_type["data"][$i]["rol"] = unserialize($user_type["data"][$i]["rol"]);
                $user_type["data"][$i]["display_name"] = URLify::slug($user_type["data"][$i]["display_name"]);
            }
        } else {
            throw new Exception("User not found");
        }
        if (IS_DEVELOPMENT) {
            krumo($user_type);
        }
        return $user_type["data"][0];
    }

    /**
     * Get orders for the current user
     * @param array $limit offset and items per page
     * @return array
     * @throws Exception
     */
    public function get_orders_current_user(array $limit = ["start" => 0, "items" => 10]): array
    {
        $user_type = $this->get_user_type($this->email);
        if (isset($user_type["rol"]["administrator"])) {
            $this->is_admin = true;
            $query = $this->admin_query;
            $count_orders_query = $this->db->get_scalar("select count(id) as orders from wp_procedure");
        } else {
            $query = $this->client_query;
            $count_orders_query = $this->db->get_scalar("select count(id) as orders from wp_procedure p
            inner join wpsw_users u on p.user_id = u.ID
            and u.user_email=$this->email");
        }
        $user_orders = $this->db->get_query($query, [
            "email" => $this->email,
            "start" => $limit["start"],
            "items" => $limit["items"]]);
        if (IS_DEVELOPMENT) {
            krumo($user_orders);
        }
        for ($i = 0; $i < count($user_orders["data"]); $i++) {
            $user_orders["data"][$i]["creation_date"] = self::createDateTimeFromString($user_orders["data"][$i]["creation_date"]);
        }

        $user_orders["stats"]["total_records"] = $count_orders_query;
        return $user_orders;
    }

    public function get_order_and_procedure(string $procedure_id): array
    {
        $this->create_procedure_if_nonexistent($procedure_id);
        $procedure_data = $this->db->get_query("select p.id,
       post_date                              as creation_date,
       order_item_name                        as name,
       post_status                            as order_status,
       concat(c.first_name, ' ', c.last_name) as user,
       c.email,
       proc.id                                as procedure_id,
       oi.order_id                            as procedure_order_id,
       creation_date                          as procedure_creation_date,
       update_date                            as procedure_update_date,
       ps.status                              as procedure_status
        from wpsw_posts p
         inner join wpsw_woocommerce_order_items oi on oi.order_id = p.ID
         inner join wpsw_postmeta wp on p.ID = wp.post_id and meta_key = '_customer_user'
         inner join wpsw_wc_customer_lookup c on c.user_id = wp.meta_value
         inner join wp_procedure proc on proc.order_id = p.ID
         inner join wp_procedure_status ps on ps.id = proc.status_id
        where p.ID=:procedure_id", ["procedure_id" => $procedure_id]);
        $procedure_files = $this->db->get_query("select pf.id, file_path, ft.id, ft.type
		from wp_procedure_file pf
         left outer join wp_procedure_file_type ft
         on pf.type = ft.id where pf.procedure_id= :procedure_id", ["procedure_id" => $procedure_data["data"][0]["procedure_id"]]);
        for ($i = 0; $i < count($procedure_data["data"]); $i++) {
            $procedure_data["data"][$i]["order_status"] = self::$order_statuses[$procedure_data["data"][$i]["order_status"]];
            $procedure_data["data"][$i]["creation_date"] = self::createDateTimeFromString($procedure_data["data"][$i]["creation_date"]);
            $procedure_data["data"][$i]["procedure_creation_date"] = self::createDateTimeFromString($procedure_data["data"][$i]["procedure_creation_date"]);
            $procedure_data["data"][$i]["procedure_update_date"] = self::createDateTimeFromString($procedure_data["data"][$i]["procedure_update_date"]);
        }
        if (WP_DEBUG) {
            krumo($procedure_data, $procedure_files);
        }

        return [
            "procedure" => $procedure_data["data"][0],
            "files" => $procedure_files["data"]
        ];
    }

    public function create_procedure(int $user_id, int $procedure_type, float $amount, string $payment_info): bool
    {
        return $this->db->execute_query(" insert into wp_procedure(user_id,status_id,procedure_type,amount,payment_info) 
 			values(:user_id, :status_id, :procedure_type, :amount, :payment_info) ",
            [
                "user_id" => $user_id,
                "status_id" => 1,
                "procedure_type" => $procedure_type,
                "amount" => $amount,
                "payment_info" => $payment_info
            ]);
    }

    public function change_procedure_status(string $procedure_id, int $status): bool
    {
        return $this->db->execute_query("update wp_procedure set status_id = :status where id = :procedure_id",
            ["status" => $status, "procedure_id" => $procedure_id]);
    }

    public function get_user_files(): array
    {
        return $this->db->get_query("select pf.id, file_path, wpft.type, document_name
        from wp_procedure_file pf
            left join wp_procedure_file_type wpft on pf.type = wpft.id
                where user_email= :user_email",
            ["user_email" => $this->email]);
    }

    public function add_user_file(string $user_email, string $file, int $type, string $document_name = ""): bool
    {
        $sql = "INSERT INTO wp_procedure_file(user_email, file_path, type, document_name) 
                VALUES(:user_email, :file, :type, :document_name)";
        return $this->db->execute_query($sql, [
            "user_email" => $user_email,
            "file" => $file,
            "type" => $type,
            "document_name" => $document_name
        ]);
    }

    public function remove_procedure_file(string $procedure_id, string $file_id): bool
    {
        return $this->db->execute_query("delete from wp_procedure_file where procedure_id = :procedure_id and id= :file_id",
            ["procedure_id" => $procedure_id, "file_id" => $file_id]);
    }

    /**
     * Gets document types from the database
     * @return array
     */
    public function get_document_types(): array
    {
        return $this->db->get_query("select id, type from wp_procedure_file_type")["data"];
    }

    /**
     * Get document types as a concatenated string
     * @return string
     */
    public function get_document_types_string(): string
    {
        $document_types = $this->get_document_types();
        $array_string = array_map(fn(array $value) => '"' . $value["type"] . '"', $document_types);
        return implode(",", $array_string);
    }

    /**
     * Get procedure types from the database
     * @return array
     */
    public function get_procedure_types(): array
    {
        return $this->db->get_query("select id, name from wp_procedure_type")["data"];
    }

    /**
     * Process the procedure
     * @param array $post procedure data
     * @param array $files procedure files
     * @return bool[]
     */
    public function process(array $post, array $files): array
    {
        if (IS_DEVELOPMENT) {
            krumo($post);
            krumo($files);
        }
        $result = [];
        $db_result = [];

        if (!empty($files)) {
            $path = "tramites" . "/" . URLify::slug($post["user"]) . "/";
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $files_count = count($files['document']['name']);
            for ($i = 0; $i < $files_count; $i++) {
                $filename = $files['document']['name'][$i];
                $result[$i] = move_uploaded_file($files['document']['tmp_name'][$i], $path . $filename);
                $db_result[$i] = $this->add_user_file($this->email,
                    $path . $filename,
                    $post["document_type"][$i],
                    $post["document_name"][$i]);
            }
        }
        if (IS_DEVELOPMENT) {
            krumo($result);
        }
        return [
            "result" => true,
            "db_result" => $db_result,
            "move_file_result" => $result
        ];
    }

    /**
     * Is user admin
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * checks if the procedure exists and creates one if non existent
     * @param string $procedure_id procedure id
     */
    private function create_procedure_if_nonexistent(string $procedure_id): void
    {
        $procedure_table = $this->db->get_query("select id from wp_procedure where order_id=:procedure_id",
            ["procedure_id" => $procedure_id]);
        if (count($procedure_table["data"]) == 0) {
            $create_procedure = $this->create_procedure(1, $procedure_id, 0, '');
            if (IS_DEVELOPMENT) {
                krumo($create_procedure);
            }
        }
    }

    /**
     * Checks if the user exists
     * @param WP_User|null $user
     * @return bool
     */
    public static function user_is_null(?WP_User $user): bool
    {
        return isset($user) && $user->ID == 0;
    }

    /**
     * Get Personal identification types
     * @return string[]
     */
    public static function get_identification__types(): array
    {
        return ["NIE" => "NIE", "DNI" => "DNI", "PASAPORTE" => "PASAPORTE"];
    }

    /**
     * Updates user information
     * @param WP_User $user target user
     * @param array $user_info new user information
     * @return int|WP_Error The updated user's ID or a WP_Error object if the user could not be updated.
     */
    public function update_user_info(WP_User $user, array $user_info)
    {
        array_pop($user_info);
        foreach ($user_info as $key => $value) {
            add_user_meta($user->ID, $key, $value, true);
        }
        $user = get_userdata($user->ID);
        $user->first_name = $_POST["first_name"];
        $user->last_name = $_POST["last_name"];
        $user->user_email = $_POST["billing_email"];
        $user->display_name = $_POST["first_name"] . " " . $_POST["last_name"];
        return wp_update_user($user);
    }
}
