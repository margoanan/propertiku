<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class TransactionStatusController extends REST_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('TransactionStatus_model');
        // Load library or helper jika diperlukan
    }

    public function index_get($status_id = null) {
        if ($status_id === null) {
            $statuses = $this->TransactionStatus_model->get_all_statuses();
            $this->response($statuses, REST_Controller::HTTP_OK);
        } else {
            $status = $this->TransactionStatus_model->get_status_by_id($status_id);
            if ($status) {
                $this->response($status, REST_Controller::HTTP_OK);
            } else {
                $this->response(['message' => 'Status not found'], REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function index_post() {
        $status_data = array(
            'status_name' => $this->post('status_name')
            // Tambahkan atribut status lainnya yang diperlukan
        );

        $created_status = $this->TransactionStatus_model->add_status($status_data);
        if ($created_status) {
            $this->response(['message' => 'Status added successfully'], REST_Controller::HTTP_CREATED);
        } else {
            $this->response(['message' => 'Failed to add status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index_put($status_id) {
        $status_data = array(
            'status_name' => $this->put('status_name')
            // Tambahkan atribut status lainnya yang diperlukan
        );

        $updated_status = $this->TransactionStatus_model->update_status($status_id, $status_data);
        if ($updated_status) {
            $this->response(['message' => 'Status updated successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['message' => 'Failed to update status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index_delete($status_id) {
        $deleted_status = $this->TransactionStatus_model->delete_status($status_id);
        if ($deleted_status) {
            $this->response(['message' => 'Status deleted successfully'], REST_Controller::HTTP_OK);
        } else {
            $this->response(['message' => 'Failed to delete status'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
?>
