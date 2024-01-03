<?php
class Property_photo_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_photos() {
        return $this->db->get('property_photos')->result();
    }

    public function get_photo_by_id($photo_id) {
        return $this->db->get_where('property_photos', array('id' => $photo_id))->row();
    }

    public function add_photo($data) {
        return $this->db->insert('property_photos', $data);
    }

    public function update_photo($photo_id, $data) {
        $this->db->where('id', $photo_id);
        return $this->db->update('property_photos', $data);
    }

    public function delete_photo($photo_id) {
        return $this->db->delete('property_photos', array('id' => $photo_id));
    }
}
?>
