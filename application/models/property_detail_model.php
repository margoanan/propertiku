<?php
class Property_detail_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_details() {
        return $this->db->get('property_details')->result();
    }

    public function get_detail_by_id($detail_id) {
        return $this->db->get_where('property_details', array('id' => $detail_id))->row();
    }

    public function add_detail($data) {
        return $this->db->insert('property_details', $data);
    }

    public function update_detail($detail_id, $data) {
        $this->db->where('id', $detail_id);
        return $this->db->update('property_details', $data);
    }

    public function delete_detail($detail_id) {
        return $this->db->delete('property_details', array('id' => $detail_id));
    }
}
?>
