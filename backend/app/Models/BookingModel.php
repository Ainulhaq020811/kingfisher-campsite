<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'booking';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['booking_number', 'lot_id', 'customer_id', 'customer_count', 'total_amount', 'rental_list', 'check_in', 'check_out', 'receipt_number'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'booking_number' => 'required|is_unique[booking.booking_number]',
        'lot_id'         => 'required|integer',
        'customer_id'    => 'required|integer',
        'customer_count' => 'required|integer',
        'total_amount'   => 'required|decimal',
        'rental_list'    => 'required|string',
        'check_in'       => 'required|valid_date[Y-m-d H:i:s]',
        'check_out'      => 'required|valid_date[Y-m-d H:i:s]',
        'receipt_number' => 'required|is_unique[booking.receipt_number]',
        ];
    protected $validationMessages   = [
        'booking_number' => [
            'required'  => 'Booking number is required.',
            'is_unique' => 'That booking number is already in use.',
        ],
        'lot_id' => [
            'required'  => 'You must specify a lot.',
            'integer'   => 'Lot ID must be an integer.',
        ],
        'customer_id' => [
            'required'  => 'You must specify a customer.',
            'integer'   => 'Customer ID must be an integer.',
        ],
        'customer_count' => [
            'required'  => 'You must specify how many guests.',
            'integer'   => 'Customer count must be an integer.',
        ],
        'total_amount' => [
            'required' => 'Total amount is required.',
            'decimal'  => 'Total amount must be a decimal number.',
        ],
        'rental_list' => [
            'required' => 'You must list the rentals.',
            'string'   => 'Rental list must be a string.',
        ],
        'check_in' => [
            'required'   => 'Check-in date/time is required.',
            'valid_date' => 'Check-in must be a valid date/time (YYYY-MM-DD HH:MM:SS).',
        ],
        'check_out' => [
            'required'   => 'Check-out date/time is required.',
            'valid_date' => 'Check-out must be a valid date/time (YYYY-MM-DD HH:MM:SS).',
        ],
        'receipt_number' => [
            'required'  => 'Receipt number is required.',
            'is_unique' => 'That receipt number is already in use.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
