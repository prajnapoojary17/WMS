<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog  extends Model
{
 
     protected $fillable = [
        'user_id','ipaddress','sequence_no','category','action'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_log';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
