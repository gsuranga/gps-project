<?php

namespace App\Xml;

use App\Models\Base;

class SalesRep extends Base
{
    protected $revisionEnabled = true;
    
    protected $table = 'ifs_sales_reps';

    protected $primaryKey = 'ifs_sales_rep_id';

    protected $fillable = [
        'sales_rep_id',
        'sales_rep_name',
        'phone_no',
        'email',
        'active_site',
        'valid_from_date',
        'block_for_use'
    ];
}
