<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function detail()
    {
        return $this->hasMany(DetailTransaction::class);
    }

    /**
     * @return BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
