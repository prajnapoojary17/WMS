<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentHistory extends Model
{
    protected $fillable = ['sequence_number',
                        'meter_reading_id',
                        'payment_date',
                        'total_amount',
                        'payment_mode',
                        'payment_status',
                        'created_at',
                        'updated_at',
                        'is_olddata'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_history';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
