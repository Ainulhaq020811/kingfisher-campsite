<?php namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\CustomerModel;

class CustomerController extends ResourceController
{
    // 1) Tell CI4 which model to use
    protected $modelName = CustomerModel::class;
    // 2) Always return JSON
    protected $format    = 'json';

    /**
     * GET /api/customer
     */
    public function index(): ResponseInterface
    {
        $data = $this->model->findAll();
        return $this->respond($data);
    }

    /**
     * GET /api/customer/{id}
     */
    public function show($id = null): ResponseInterface
    {
        $item = $this->model->find($id);
        if (!$item) {
            return $this->failNotFound("Customer with ID $id not found");
        }
        return $this->respond($item);
    }

    /**
     * POST /api/customer
     */
    public function create(): ResponseInterface
    {
        $payload = $this->request->getJSON(true);

        if (!$this->model->insert($payload)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $newId   = $this->model->getInsertID();
        $newItem = $this->model->find($newId);

        return $this->respondCreated($newItem);
    }

    /**
     * PUT/PATCH /api/customer/{id}
     */
    public function update($id = null): ResponseInterface
    {
        $exists  = $this->model->find($id);
        if (!$exists) {
            return $this->failNotFound("Customer with ID $id not found");
        }

        $payload = $this->request->getJSON(true);
        if (!$this->model->update($id, $payload)) {
            return $this->failValidationErrors($this->model->errors());
        }

        $updated = $this->model->find($id);
        return $this->respond($updated);
    }

    /**
     * DELETE /api/customer/{id}
     */
    public function delete($id = null): ResponseInterface
    {
        $exists = $this->model->find($id);
        if (!$exists) {
            return $this->failNotFound("Customer with ID $id not found");
        }

        if ($this->model->delete($id)) {
            return $this->respondDeleted(['id' => $id]);
        }

        return $this->failServerError("Failed to delete customer $id");
    }
}
