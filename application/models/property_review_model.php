<?php
class Property_review_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_reviews() {
        return $this->db->get('property_reviews')->result();
    }

    public function get_review_by_id($review_id) {
        return $this->db->get_where('property_reviews', array('id' => $review_id))->row();
    }

    public function add_review($data) {
        return $this->db->insert('property_reviews', $data);
    }

    public function update_review($review_id, $data) {
        $this->db->where('id', $review_id);
        return $this->db->update('property_reviews', $data);
    }

    public function delete_review($review_id) {
        return $this->db->delete('property_reviews', array('id' => $review_id));
    }
}
?>
