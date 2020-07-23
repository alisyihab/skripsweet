<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];
    protected $appends = ['type_label'];

    public function getTypeLabelAttribute()
    {
        if (filter_var($this->type == false, FILTER_VALIDATE_BOOLEAN)) {
            return 'Cash';
        }
        return 'Deposit';
    }
}
