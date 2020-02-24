<?php


namespace SlashEbc\Database;


class DonationsModel
{
    private $table;
    private $primaryKey = 'id';
    private $db;

    public function __construct()
    {
        global $wpdb, $table_prefix;
        $this->table = $table_prefix . "ebc_donations";
        $this->db = $wpdb;
    }

    public function fetchDonations()
    {
        return $this->db->get_results("SELECT * FROM {$this->table} ORDER BY transaction_id DESC");
    }

    public function getByTransactionId($transaction_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE transaction_id = %s";
        $query = $this->db->prepare($sql, [$transaction_id]);
        return $this->db->get_row($query);
    }

    public function insert($data)
    {
        if (empty($data)) return false;
        return $this->db->insert($this->table, $data);
    }

    public function update(array $data, array $whereClause)
    {
        return $this->db->update($this->table, $data, $whereClause);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, [$this->primaryKey => $id]);
    }
}