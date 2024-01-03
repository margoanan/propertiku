<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;


class Property extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Property_model'); // Memuat model properti
        // $this->load->library('Authorization_Token'); // Memuat library JWT jika diperlukan
    }

    public function index_get($property_id = null) {
        if ($property_id === null) {
            $properties = $this->Property_model->get_all_properties();
            $this->response($properties, REST_Controller::HTTP_OK);
        } else {
            $property = $this->Property_model->get_property_by_id($property_id);
            if ($property) {
                $this->response($property, REST_Controller::HTTP_OK);
            } else {
                $this->response(['message' => 'Property not found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_post() {
    // Ambil data dari input POST
    $property_data = array(
        'nama' => $this->post('nama'),
        'deskripsi' => $this->post('deskripsi'),
        'harga' => $this->post('harga'),
        'lokasi' => $this->post('lokasi'),
        'tipe_properti' => $this->post('tipe_properti'),
        'tanggal_dibuat' => date('Y-m-d H:i:s'),  // Menambahkan timestamp created_at
    );


    // Pastikan data penting tidak kosong
    if (!empty($property_data['nama']) && !empty($property_data['deskripsi']) && !empty($property_data['harga']) && !empty($property_data['lokasi']) && !empty($property_data['tipe_properti'])) {
        // Panggil model untuk menambah properti baru ke dalam database
        $created_property = $this->Property_model->add_property($property_data);
        if ($created_property) {
            $this->response(['message' => 'Property added successfully'], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['message' => 'Failed to add property'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    } else {
        $this->response(['message' => 'Please provide all required property details'], REST_Controller::HTTP_BAD_REQUEST);
    }
}


public function index_put($property_id) {
    // Ambil data dari input PUT
    $property_data = array(
        'nama' => $this->put('nama'),
        'deskripsi' => $this->put('deskripsi'),
        'harga' => $this->put('harga'),
        'lokasi' => $this->put('lokasi'),
        'tipe_properti' => $this->put('tipe_properti'),
        'foto' => $this->put('foto'),
        // Tambahkan atribut properti lainnya yang sesuai dengan struktur tabel
    );

    // Pastikan data penting tidak kosong
    if (!empty($property_data['nama']) && !empty($property_data['deskripsi']) && !empty($property_data['harga']) && !empty($property_data['lokasi']) && !empty($property_data['tipe_properti'])) {
        // Panggil model untuk memperbarui properti berdasarkan ID di dalam database
        $updated_property = $this->Property_model->update_property($property_id, $property_data);
        if ($updated_property) {
            $this->response(['message' => 'Property updated successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['message' => 'Failed to update property'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    } else {
        $this->response(['message' => 'Please provide all required property details'], REST_Controller::HTTP_BAD_REQUEST);
    }
}


    public function index_delete($property_id) {
        // Logic untuk menghapus properti berdasarkan ID
        $deleted_property = $this->Property_model->delete_property($property_id);
        if ($deleted_property) {
            $this->response(['message' => 'Property deleted successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['message' => 'Failed to delete property'], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
