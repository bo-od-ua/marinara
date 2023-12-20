<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'name_alt',
        'phone',
        'phone_alt',
        'car_info',
        'storage_time',
        'price',
        'paid',
        'descr_category',
        'descr_name',
        'descr_notise',
        'descr_amount',
    ];

    public function setPaid($value)
    {
        $this->attributes['paid'] = (int)$value;
    }
    public function setStorageTime($value)
    {
        $this->attributes['	storage_time'] = str_replace('/', '-');
    }
}
