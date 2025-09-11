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
    public function create_table() : void
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
}
