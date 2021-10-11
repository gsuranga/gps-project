<?php

namespace App\Xml;

use App\Models\Base;

class SalesReturnBlockItemParts extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_sales_return_block_item_parts';
    protected $primaryKey = 'ifs_sales_return_block_part_id';

    protected $fillable = [
        'site_cluster_id',
        'contract'
    ];
}
