<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Property extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Property_model');
        $this->property_model = $this->load->model('Property_model');

        // $this->load->library('cors');
        // $this->cors->handle();
        // $this->load->library('session'); // Memuat library Session
    }

    private function is_user_logged_in() {
        // return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
        return TRUE;
    }

    public function index_get($property_id = null) {
        if ($this->is_user_logged_in()) {
            if ($property_id === null) {
                $properties = $this->Property_model->get_all_properties();
                $this->response($properties, REST_Controller::HTTP_OK);
            } else {
                // $property = $this->property_model->get_property_by_id($property_id);
                // if ($property) {
                //     $this->response($property, REST_Controller::HTTP_OK);
                // } else {
                //     $this->response(['message' => 'Property not found'], REST_Controller::HTTP_NOT_FOUND);
                // }
            }
        } else {
            $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function index_post() {
        if ($this->is_user_logged_in()) {
            $property_data = array( 
                'nama' => $this->post('nama'),
                'deskripsi' => $this->post('deskripsi'),
                'harga' => $this->post('harga'),
                'lokasi' => $this->post('lokasi'),
                'tipe_properti' => $this->post('tipe_properti'),
                'stok_unit' => $this->post('stok_unit'),
                'tanggal_dibuat' => date('Y-m-d H:i:s')
            );

            if (!empty($property_data['nama']) && !empty($property_data['deskripsi']) && !empty($property_data['harga']) && !empty($property_data['lokasi']) && !empty($property_data['tipe_properti'])) {
                $created_property = $this->Property_model->add_property($property_data);
                if ($created_property) {
                    $this->response(['message' => 'Property added successfully'], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response(['message' => 'Failed to add property'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response(['message' => 'Please provide all required property details'], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function index_put($property_id) {
        if ($this->is_user_logged_in()) {
            $property_data = array(
                'nama' => $this->put('nama'),
                'deskripsi' => $this->put('deskripsi'),
                'harga' => $this->put('harga'),
                'lokasi' => $this->put('lokasi'),
                'tipe_properti' => $this->put('tipe_properti'),
                'stok_unit' => $this->put('stok_unit'),
                'tanggal_dibuat' => date('Y-m-d H:i:s')
            );
    
            if (!empty($property_data['nama']) && !empty($property_data['deskripsi']) && !empty($property_data['harga']) && !empty($property_data['lokasi']) && !empty($property_data['tipe_properti'])) {
                $updated_property = $this->Property_model->update_property($property_id, $property_data);
                if ($updated_property) {
                    $this->response(['message' => 'Property updated successfully'], REST_Controller::HTTP_OK);
                } else {
                    $this->response(['message' => 'Failed to update property'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response(['message' => 'Please provide all required property details'], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    
    

    public function index_delete($property_id) {
        // if ($this->is_user_logged_in()) {
            $deleted_property = $this->Property_model->delete_property($property_id);
            if ($deleted_property) {
                $this->response(['message' => 'Property deleted successfully'], REST_Controller::HTTP_OK);
            } else {
                $this->response(['message' => 'Failed to delete property'], REST_Controller::HTTP_BAD_REQUEST);
            }
        // } else {
        //     $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        // }
    }

    public function index_delete_property($property_id) {
        // Hapus review dan transaksi yang terkait dengan properti
        $this->property_model->delete_related_reviews($property_id);
        $this->property_model->delete_related_transactions($property_id);
    
        // Hapus properti itu sendiri
        $result = $this->property_model->delete_property($property_id);
    
        if ($result) {
            $this->response(['message' => 'Property deleted successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['message' => 'Failed to delete property'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function get_property_by_id($property_id) {
        $this->db->get_where('properties', array('id' => $property_id))->row();
    }
    
}

