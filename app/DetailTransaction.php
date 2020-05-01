<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaction extends Model
{
    protected $guarded = [];
    protected $dates = ['start_date', 'end_date'];
    protected $appends = ['service_time', 'status_label'];

    /**
     * @return string
     */
    public function getServiceTimeAttribute()
    {
        return $this->start_date->format('d-m-Y H:i:s') . 's/d' . $this->end_date->format('d-m-Y H:i:s');
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

    /**
     * @return BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * @return BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(LaundryPrice::class, 'laundry_price_id');
    }
}