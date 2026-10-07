<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceReportPart extends Model
{
    protected $fillable = ['service_report_id', 'name', 'quantity', 'unit_cost', 'line_total'];

    public function report()
    {
        return $this->belongsTo(ServiceReport::class, 'service_report_id');
    }
}