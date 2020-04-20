<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use Notifiable;

    protected $table = 'customers';
    protected $guarded = [];

    public function courier()
    {
        return $this->belongsTo(User::class);
    }
}
