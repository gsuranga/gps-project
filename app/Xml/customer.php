<?php
namespace App\Xml;

use App\Models\Base;

class Customer extends Base{

    protected $revisionEnabled = true;
    
    protected $table = 'ifs_customers';

    protected $primaryKey = 'ifs_cus_id';

    protected $fillable = [
        'customer_id',
        'name',
        'association_no',
        'reference',
        'cust_price_group_id',
        'cust_price_group_desc',
        'cust_category1_id',
        'cust_category1_desc',
        'cust_category2_id',
        'cust_category2_desc',
        'cust_district_id',
        'cust_district_desc',
        'cust_town_id',
        'cust_town_desc',
        'cust_region_id',
        'cust_region_desc',
        'cust_route_id',
        'cust_route_desc',
        'cust_business_sec_id',
        'cust_business_sec_desc',
        'cust_main_channel_id',
        'cust_main_channel_desc',
        'cust_sub_channel_id',
        'cust_sub_channel_desc',
        'credit_blocked',
        'credit_limit',
        'credit_blocked_open_invoice',
        'payment_term_id',
        'payment_term_description',
        'payment_term_no_of_days',
        'currency_code',
        'vat_no',
        'active',
        'address_id',
        'address_1',
        'address_2',
        'zip_code',
        'city',
        'county',
        'state',
        'country',
        'phone',
        'mobile',
        'email',
        'contract',
        'default',
        'bank_acc_id',
        'account_code',
        'bank_acc_status'

    ];
}