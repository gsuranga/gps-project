<?php 

namespace App\Xml;

use App\Models\Base;

class PriceListDealer extends Base{

    protected $revisionEnabled = true;
    
    protected $table = 'ifs_price_list_dealer';

    protected $primaryKey = 'ifs_pld_id';

    protected $fillable = [
        'currency_code',
        'price_list_no',
        'price_list_description',
        'catalog_no',
        'valid_from',
        'valid_to',
        'price_net',
        'vat',
        'price',
        'discount_type',
        'discount_type_desc',
        'discount'
    ];
}