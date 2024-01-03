<?php
class Review_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database(); // Initialize the database
    }

    public function get_all_reviews() {
        return $this->db->get('property_reviews')->result();
    }

    public function get_reviews_by_property_id($property_id) {
        return $this->db->get_where('property_reviews', array('property_id' => $property_id))->result();
    }

    public function add_review($data) {
        $this->db->insert('property_reviews', $data);
        return $this->db->insert_id(); // Return the ID of the newly inserted record
    }

    public function update_reviews($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('property_reviews', $data);
    }

    public function delete_reviews($id) {
        return $this->db->delete('property_reviews', array('id' => $id));
    }

    public function delete_related_reviews($property_id) {
        // Hapus review yang terkait dengan properti
        return $this->db->delete('property_reviews', array('property_id' => $property_id));
    }
}
?>
