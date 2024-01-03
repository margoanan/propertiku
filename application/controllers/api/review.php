<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Review extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Review_model');
    }

    private function is_user_logged_in() {
        // Add your authentication logic here
        return TRUE;
    }

    public function index_get($property_id = null) {
        if ($this->is_user_logged_in()) {
            if ($property_id === null) {
                // Retrieve all reviews
                $reviews = $this->Review_model->get_all_reviews();
                $this->response($reviews, REST_Controller::HTTP_OK);
            } else {
                // Retrieve reviews for a specific property
                $property_reviews = $this->Review_model->get_reviews_by_property_id($property_id);
                $this->response($property_reviews, REST_Controller::HTTP_OK);
            }
        } else {
            $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function index_post() {
        if ($this->is_user_logged_in()) {
            // Add a new review
            $review_data = array(
                'property_id' => $this->post('property_id'),
                'user_id' => $this->post('user_id'),
                'rating' => $this->post('rating'),
                'review_text' => $this->post('review_text'),
            );

            // Validate input data
            if (!empty($review_data['property_id']) && !empty($review_data['user_id']) && isset($review_data['rating'])) {
                $created_review_id = $this->Review_model->add_review($review_data);
                if ($created_review_id) {
                    $this->response(['message' => 'Review added successfully', 'id' => $created_review_id], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response(['message' => 'Failed to add review'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response(['message' => 'Please provide all required review details'], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function index_put($id = null) {
        if ($this->is_user_logged_in()) {
            // Update an existing review
            $review_data = array(
                'rating' => $this->put('rating'),
                'review_text' => $this->put('review_text'),
            );
    
            // Validate input data
            if (!empty($id) && (isset($review_data['rating']) || !empty($review_data['review_text']))) {
                $updated = $this->Review_model->update_reviews($id, $review_data);
                if ($updated) {
                    $this->response(['message' => 'Review updated successfully'], REST_Controller::HTTP_OK);
                } else {
                    $this->response(['message' => 'Failed to update review'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response(['message' => 'Please provide the review ID and at least one field to update'], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    

    public function index_delete($id) {
        if ($this->is_user_logged_in()) {
            // Delete an existing review
            if (!empty($id)) {
                $deleted = $this->Review_model->delete_reviews($id);
                if ($deleted) {
                    $this->response(['message' => 'Review deleted successfully'], REST_Controller::HTTP_OK);
                } else {
                    $this->response(['message' => 'Failed to delete review'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response(['message' => 'Please provide the review ID'], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response(['message' => 'Unauthorized access'], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function delete_related_data($property_id) {
        // Panggil metode penghapusan terkait dari model
        $this->Transaction_model->delete_related_reviews($property_id);
    
        // Tambahkan metode penghapusan terkait dari model Property_model jika ada
        // $this->Property_model->delete_related_reviews($property_id);
    
        // Kembalikan respons atau arahkan pengguna ke halaman yang sesuai
    }
}
?>
