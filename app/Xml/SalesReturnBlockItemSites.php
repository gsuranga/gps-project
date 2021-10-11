<?php

namespace App\Xml;

use App\Models\Base;

class SalesReturnBlockItemSites extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_sales_return_block_item_Sites';
    protected $primaryKey = 'ifs_sales_return_block_site_id';

    protected $fillable = [
        'site_cluster_id',
        'contract'
    ];
}
