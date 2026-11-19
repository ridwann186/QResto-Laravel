<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Order;

class RestaurantTable extends Model
{
    protected $fillable = [
        'code',
        'name',
        'qr_token',
        'status',
    ];

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function orders(): HasMany
{
    return $this->hasMany(Order::class, 'table_id');
}
}