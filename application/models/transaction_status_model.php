<?php
class Transaction_status_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_statuses() {
        return $this->db->get('transaction_statuses')->result();
    }

    public function get_status_by_id($status_id) {
        return $this->db->get_where('transaction_statuses', array('id' => $status_id))->row();
    }

    public function add_status($data) {
        return $this->db->insert('transaction_statuses', $data);
    }

    public function update_status($status_id, $data) {
        $this->db->where('id', $status_id);
        return $this->db->update('transaction_statuses', $data);
    }

    public function delete_status($status_id) {
        return $this->db->delete('transaction_statuses', array('id' => $status_id));
    }
}
?>
