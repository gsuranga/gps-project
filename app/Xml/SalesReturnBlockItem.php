<?php

namespace App\Xml;

use App\Models\Base;

class SalesReturnBlockItem extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_sales_return_block_items';
    protected $primaryKey = 'ifs_sales_return_block_item_id';

    protected $fillable = [
        'site_cluster_id',
        'site_cluster_desc'
    ];
}
