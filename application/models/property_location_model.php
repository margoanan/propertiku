<?php
class Property_location_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_locations() {
        return $this->db->get('property_locations')->result();
    }

    public function get_location_by_id($location_id) {
        return $this->db->get_where('property_locations', array('id' => $location_id))->row();
    }

    public function add_location($data) {
        return $this->db->insert('property_locations', $data);
    }

    public function update_location($location_id, $data) {
        $this->db->where('id', $location_id);
        return $this->db->update('property_locations', $data);
    }

    public function delete_location($location_id) {
        return $this->db->delete('property_locations', array('id' => $location_id));
    }
}
?>
