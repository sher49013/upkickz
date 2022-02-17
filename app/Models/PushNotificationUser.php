<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PushNotificationUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "device_token",
        "device_id",
        "user_id",
        "status",
        "date_updated",
        "push_platform_id"
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
