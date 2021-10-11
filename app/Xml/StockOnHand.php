<?php

namespace App\Xml;

use App\Models\Base;

class StockOnHand extends Base
{
    protected $revisionEnabled = true;
    
    protected $table = 'ifs_stock_on_hands';

    protected $primaryKey = 'ifs_stock_id';

    protected $fillable = [
        'part_no',
        'contract',
        'location_no',
        'qty',
        'open_order_qty'
    ];
}
