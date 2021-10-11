<?php 
namespace App\Xml;

use App\Models\Base;

class Item extends Base{

    protected $revisionEnabled = true;
    
    protected $table = 'ifs_products';

    protected $primaryKey = 'ifs_product_id';

    protected $fillable = [

        'contract',
        'catalog_no',
        'catalog_desc',
        'part_main_group',
        'part_main_group_desc',
        'style_no',
        'style_desc',

        'catalog_group',
        'catalog_group_desc',
        'sub_category',
        'sub_category_desc',
        'category',
        'category_desc',

        'brand',
        'brand_desc',
        'supplier_article_no',
        'part_type',
        'unit',
        'vat_code',
        'price_net',
        'vat',
        'price',
        'active',
        'currency_code',
        'sales_start',

        'block_for_sales',
        'block_for_sales_return'
    ];
}