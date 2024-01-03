<?php
class Transaction_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getAllTransactions() {
        return $this->db->get('transactions')->result();
    }

    public function getTransactionById($transaction_id) {
        return $this->db->get_where('transactions', array('id' => $transaction_id))->row();
    }

    public function addTransaction($data) {
        $property = $this->db->get_where('properties', array('id' => $data['id_properti']))->row();
        $jumlah_dibayar = $data['jumlah'] * $property->harga;
        $new_stok = $property->stok_unit - $data['jumlah'];

        $transaction = array(
            'id_properti' => $data['id_properti'],
            'jumlah_dibayar' => $jumlah_dibayar,
            'metode_pembayaran' => $data['metode_pembayaran'],
            'tanggal_transaksi' => date('Y-m-d H:i:s'), // Menyimpan timestamp saat ini,
            'jumlah' => $data['jumlah']
        );

        $this->db->trans_start();
        $this->db->insert('transactions', $transaction);
        $this->db->where('id', $data['id_properti']);
        $this->db->update('properties', array('stok_unit' => $new_stok));
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function deleteTransaction($transaction_id) {
        return $this->db->delete('transactions', array('id' => $transaction_id));
    }
    
    public function delete_related_transactions($transaction_id) {
        // Hapus transaksi yang terkait dengan properti
        return $this->db->delete('transactions', array('property_id' => $property_id));
    }

    
}
?>
