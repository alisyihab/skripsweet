<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    protected $guarded = [];
    protected $appends = ['status_label'];

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
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        if ($this->status == 1) {
            return '<span class="badge badge-success">Selesai</span>';
        }
        return '<span class="badge badge-primary">Proses</span>';
    }
}