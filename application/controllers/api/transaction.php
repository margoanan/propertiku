<?php
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Transaction extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model', 'transaction');
        $this->load->library('form_validation');
        $this->property_model = $this->load->model('Property_model');

        // $this->load->library('cors'); // Memuat library CORS

        // // Konfigurasi CORS
        // $this->cors->options([
        //     'allowed_origins' => ['*'], 
        //     'allowed_methods' => ['GET', 'POST', 'DELETE'], 
        //     'allow_headers' => ['Content-Type', 'Authorization'], // Header yang diizinkan
        //     'expose_headers' => ['Content-Type'], // Header yang diperbolehkan diakses dari kode JavaScript di sisi klien
        //     'max_age' => 3600, // Durasi dalam detik sebelum browser harus meminta informasi CORS lagi
        //     'always_send' => false, // Jika true, akan selalu mengirim header CORS, bahkan jika tidak ada permintaan CORS yang terdeteksi
        //     'is_cli' => false // Menggunakan metode CORS ketika dijalankan di CLI
        // ]);
        // $this->cors->handle(); // Mengaktifkan CORS
    }


    public function index_get($transaction_id = null) {
        if ($transaction_id === null) {
            // Ambil semua transaksi
            $transactions = $this->transaction->getAllTransactions();
            if ($transactions) {
                $this->response($transactions, REST_Controller::HTTP_OK);
            } else {
                $this->response(['message' => 'No transactions found'], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            // Ambil transaksi berdasarkan ID
            $transaction = $this->transaction->getTransactionById($transaction_id);
            if ($transaction) {
                $this->response($transaction, REST_Controller::HTTP_OK);
            } else {
                $this->response(['message' => 'Transaction not found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_get_property($property_id = null) {
        if ($this->is_user_logged_in()) {
            if ($property_id === null) {
                $properties = $this->Property_model->get_all_properties();
                $this->response($properties, REST_Controller::HTTP_OK);
            } else {
                $property = $this->property_model->get_property_by_id($property_id);
                if ($property) {
                    $this->response($property, REST_Controller::HTTP_OK);
                } else {
                    $this->response(['message' => 'Property not found'], REST_Controller::HTTP_NOT_FOUND);
                }
            }
        } else {
            $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        }    
    }

    public function index_post() {
        $input = $this->post();
        $result = $this->transaction->addTransaction($input);
        if ($result) {
            $this->response(['message' => 'Transaction added successfully'], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['message' => 'Failed to add transaction'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete($transaction_id) {
        if ($transaction_id === null) {
            $this->response(['message' => 'Transaction ID not provided'], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $deleted_transaction = $this->transaction->deleteTransaction($transaction_id);
            if ($deleted_transaction) {
                $this->response(['message' => 'Transaction deleted successfully'], REST_Controller::HTTP_OK);
            } else {
                $this->response(['message' => 'Failed to delete transaction'], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
    public function delete_related_data($property_id) {
        // Panggil metode penghapusan terkait dari model
        $this->Transaction_model->delete_related_transactions($property_id);
    
        // Tambahkan metode penghapusan terkait dari model Property_model jika ada
        // $this->Property_model->delete_related_reviews($property_id);
    
        // Kembalikan respons atau arahkan pengguna ke halaman yang sesuai
    }
    
    
    
    
    
}
?>
