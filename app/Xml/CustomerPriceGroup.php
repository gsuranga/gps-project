<?php

namespace App\Xml;

use App\Models\Base;

class CustomerPriceGroup extends Base
{
    protected $revisioEnabled = true;

    protected $table = 'ifs_customer_price_groups';

    protected $primaryKey = 'ifs_cus_grp_id';
    
    protected $fillable = [
        'cust_price_group_id',
        'cust_price_group_description',
        'price_list_no',
        'price_list_description',
    ];
    
}
