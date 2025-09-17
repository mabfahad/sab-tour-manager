<?php

namespace controller;

class Sab_Db
{
    /**
     * WordPress database object
     */
    private $wpdb;

    /**
     * Table name
     * @var string
     */
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $this->wpdb->prefix . 'sab_tour_manager';
    }

    /**
     * Create table
     */
    public function create_table(): void
    {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            trip_id mediumint(9) NOT NULL,
            first_name varchar(100) NOT NULL,
            surname varchar(100) NOT NULL,
            phone varchar(50) NOT NULL,
            email varchar(100) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }

    public function save_trip_contact($data)
{
    // Validate required fields
    if (empty($data['trip_id']) || empty($data['first_name']) || empty($data['surname']) || empty($data['email'])) {
        return false;
    }

    $table = $this->table_name;

    // Sanitize inputs
    $trip_id    = intval($data['trip_id']);
    $first_name = sanitize_text_field($data['first_name']);
    $surname    = sanitize_text_field($data['surname']);
    $phone      = isset($data['phone']) ? sanitize_text_field($data['phone']) : '';
    $email      = sanitize_email($data['email']);

    // Prepare SQL query
    $sql = $this->wpdb->prepare(
        "INSERT INTO {$table} (trip_id, first_name, surname, phone, email) VALUES (%d, %s, %s, %s, %s)",
        $trip_id,
        $first_name,
        $surname,
        $phone,
        $email
    );

    // Execute query
    $result = $this->wpdb->query($sql);

    if ($result !== false) {
        return (int)$this->wpdb->insert_id; // Return inserted row ID
    }

    return false;
}


}
