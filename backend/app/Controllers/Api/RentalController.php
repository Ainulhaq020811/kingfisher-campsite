<?php namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\RentalModel;

class RentalController extends ResourceController
{
    // 1) Specify the associated model
    protected $modelName = RentalModel::class;
    // 2) Always return JSON
    protected $format    = 'json';

    /**
     * GET /api/rental
     */
    public function index(): ResponseInterface
    {
        $rentals = $this->model->findAll();
        return $this->respond($rentals);
    }

    /**
     * GET /api/rental/{id}
     */
    public function show($id = null): ResponseInterface
    {
        $rental = $this->model->find($id);
        if (! $rental) {
            return $this->failNotFound("Rental with ID $id not found");
        }
        return $this->respond($rental);
    }

    /**
     * POST /api/rental
     */
    public function create(): ResponseInterface
    {
        $payload = $this->request->getJSON(true);
        if (! $this->model->insert($payload)) {
            return $this->failValidationErrors($this->model->errors());
        }
        $id      = $this->model->getInsertID();
        $created = $this->model->find($id);
        return $this->respondCreated($created);
    }

    /**
     * PUT/PATCH /api/rental/{id}
     */
    public function update($id = null): ResponseInterface
    {
        $existing = $this->model->find($id);
        if (! $existing) {
            return $this->failNotFound("Rental with ID $id not found");
        }
        $payload = $this->request->getJSON(true);
        if (! $this->model->update($id, $payload)) {
            return $this->failValidationErrors($this->model->errors());
        }
        $updated = $this->model->find($id);
        return $this->respond($updated);
    }

    /**
     * DELETE /api/rental/{id}
     */
    public function delete($id = null): ResponseInterface
    {
        $existing = $this->model->find($id);
        if (! $existing) {
            return $this->failNotFound("Rental with ID $id not found");
        }
        if ($this->model->delete($id)) {
            return $this->respondDeleted(['id' => $id]);
        }
        return $this->failServerError("Failed to delete rental $id");
    }
}
