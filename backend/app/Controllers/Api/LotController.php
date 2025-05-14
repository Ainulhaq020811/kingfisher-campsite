<?php namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\LotModel;

class LotController extends ResourceController
{
    // 1) Specify associated model
    protected $modelName = LotModel::class;
    // 2) Always return JSON
    protected $format    = 'json';

    /**
     * GET /api/lot
     */
    public function index(): ResponseInterface
    {
        $lots = $this->model->findAll();
        return $this->respond($lots);
    }

    /**
     * GET /api/lot/{id}
     */
    public function show($id = null): ResponseInterface
    {
        $lot = $this->model->find($id);
        if (! $lot) {
            return $this->failNotFound("Lot with ID $id not found");
        }
        return $this->respond($lot);
    }

    /**
     * POST /api/lot
     */
    public function create(): ResponseInterface
    {
        $payload = $this->request->getJSON(true);
        if (! $this->model->insert($payload)) {
            return $this->failValidationErrors($this->model->errors());
        }
        $id    = $this->model->getInsertID();
        $new   = $this->model->find($id);
        return $this->respondCreated($new);
    }

    /**
     * PUT/PATCH /api/lot/{id}
     */
    public function update($id = null): ResponseInterface
    {
        $existing = $this->model->find($id);
        if (! $existing) {
            return $this->failNotFound("Lot with ID $id not found");
        }

        $payload = $this->request->getJSON(true);
        if (! $this->model->update($id, $payload)) {
            return $this->failValidationErrors($this->model->errors());
        }
        $updated = $this->model->find($id);
        return $this->respond($updated);
    }

    /**
     * DELETE /api/lot/{id}
     */
    public function delete($id = null): ResponseInterface
    {
        $existing = $this->model->find($id);
        if (! $existing) {
            return $this->failNotFound("Lot with ID $id not found");
        }
        if ($this->model->delete($id)) {
            return $this->respondDeleted(['id' => $id]);
        }
        return $this->failServerError("Failed to delete lot $id");
    }
}
