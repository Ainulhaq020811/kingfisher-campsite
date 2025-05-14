<?php namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\BookingModel;

class BookingController extends ResourceController
{
    // Specify the model and response format
    protected $modelName = BookingModel::class;
    protected $format    = 'json';

    /**
     * GET /api/booking
     * List all bookings
     *
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        $bookings = $this->model->findAll();
        return $this->respond($bookings);
    }

    /**
     * GET /api/booking/{id}
     * Retrieve a single booking by ID
     *
     * @param int|string|null $id
     * @return ResponseInterface
     */
    public function show($id = null): ResponseInterface
    {
        $booking = $this->model->find($id);
        if (! $booking) {
            return $this->failNotFound("Booking with ID $id not found");
        }
        return $this->respond($booking);
    }

    /**
     * POST /api/booking
     * Create a new booking record with validation
     *
     * @return ResponseInterface
     */
    public function create(): ResponseInterface
    {
        $data = $this->request->getJSON(true);

        // Validate and insert
        if (! $this->model->insert($data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $newId   = $this->model->getInsertID();
        $newItem = $this->model->find($newId);
        return $this->respondCreated($newItem);
    }

    /**
     * PUT/PATCH /api/booking/{id}
     * Update an existing booking
     *
     * @param int|string|null $id
     * @return ResponseInterface
     */
    public function update($id = null): ResponseInterface
    {
        $existing = $this->model->find($id);
        if (! $existing) {
            return $this->failNotFound("Booking with ID $id not found");
        }

        $data = $this->request->getJSON(true);
        if (! $this->model->update($id, $data)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $updated = $this->model->find($id);
        return $this->respond($updated);
    }

    /**
     * DELETE /api/booking/{id}
     * Delete a booking
     *
     * @param int|string|null $id
     * @return ResponseInterface
     */
    public function delete($id = null): ResponseInterface
    {
        $existing = $this->model->find($id);
        if (! $existing) {
            return $this->failNotFound("Booking with ID $id not found");
        }

        if ($this->model->delete($id)) {
            return $this->respondDeleted(['id' => $id]);
        }

        return $this->failServerError("Failed to delete booking with ID $id");
    }
}
