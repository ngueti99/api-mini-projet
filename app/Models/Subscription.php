<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function service() {
        return $this->belongsTo(Service::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function payment() {
        return $this->belongsTo(Payment::class);
    }
}
