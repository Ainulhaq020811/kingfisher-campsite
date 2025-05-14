<?php namespace App\Controllers\Api;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ZoneModel;

class ZoneController extends ResourceController
{
    // 1) Specify the associated model
    protected $modelName = ZoneModel::class;
    // 2) Always return JSON
    protected $format    = 'json';

    /**
     * GET /api/zone
     */
    public function index(): ResponseInterface
    {
        $zones = $this->model->findAll();
        return $this->respond($zones);
    }

    /**
     * GET /api/zone/{id}
     */
    public function show($id = null): ResponseInterface
    {
        $zone = $this->model->find($id);
        if (! $zone) {
            return $this->failNotFound("Zone with ID $id not found");
        }
        return $this->respond($zone);
    }

    /**
     * POST /api/zone
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
     * PUT/PATCH /api/zone/{id}
     */
    public function update($id = null): ResponseInterface
    {
        $existing = $this->model->find($id);
        if (! $existing) {
            return $this->failNotFound("Zone with ID $id not found");
        }

        $payload = $this->request->getJSON(true);
        if (! $this->model->update($id, $payload)) {
            return $this->failValidationErrors($this->model->errors());
        }
        $updated = $this->model->find($id);
        return $this->respond($updated);
    }

    /**
     * DELETE /api/zone/{id}
     */
    public function delete($id = null): ResponseInterface
    {
        $existing = $this->model->find($id);
        if (! $existing) {
            return $this->failNotFound("Zone with ID $id not found");
        }
        if ($this->model->delete($id)) {
            return $this->respondDeleted(['id' => $id]);
        }
        return $this->failServerError("Failed to delete zone $id");
    }
}