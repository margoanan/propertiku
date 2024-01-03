<?php
class Property_category_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }

    // Mendapatkan semua kategori properti
    public function get_all_categories() {
        return $this->db->get('property_categories')->result();
    }

    // Mendapatkan kategori berdasarkan ID
    public function get_category_by_id($category_id) {
        return $this->db->get_where('property_categories', array('id' => $category_id))->row();
    }

    // Menambahkan kategori baru
    public function add_category($data) {
        return $this->db->insert('property_categories', $data);
    }

    // Mengupdate kategori berdasarkan ID
    public function update_category($category_id, $data) {
        $this->db->where('id', $category_id);
        return $this->db->update('property_categories', $data);
    }

    // Menghapus kategori berdasarkan ID
    public function delete_category($category_id) {
        return $this->db->delete('property_categories', array('id' => $category_id));
    }
}
?>
