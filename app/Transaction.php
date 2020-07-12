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
        if ($this->status == 2) {
            return 
                '<span class="badge badge-success">
                        <i class="fas fa-check"></i>
                        Pembayaran 
                </span>'
            ;
        }

        if ($this->status == 1) {
            return 
                '<span class="badge badge-primary">
                        <i class="fas fa-eye"></i>
                        Pengecekan pembayaran
                </span>'
            ;
        }
        return '<span class="badge badge-warning">Proses Pembayaran</span>';
    }

    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])
        ->format('d, M Y H:i');
    }
}