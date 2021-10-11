<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use XmlParser;
use \App\Xml\SalesReturnBlockItemParts;
use \App\Xml\SalesReturnBlockItemSites;
use \App\Xml\CustomerOutstandingInvoiceList;

class integrationXMLDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:xml {arg1?}';

    /**
     * Table names in underscore notation
     */
    protected $files = [
        'item',
        'price_list_dealer',
        'customer',
        'customer_price_group',
        'sales_rep',
        'tm_rsm',
        'customer_outstanding_invoice',
        'customer_bank_details',
        'volume_discount',
        'stock_on_hand',
        'vat_details',
        'return_reason',
        'sales_return_block_item',
        'site_location'
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronizing data from XML files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Starting the xml integrations.");

        $arg1 = $this->argument('arg1');

        $xmlFiles = [];
        if($arg1&&$arg1!='truncate'){
            $xmlFiles = explode(',',$arg1);
        } else {
            $xmlFiles = $this->files;
        }

        foreach ($xmlFiles as $name) {
            $this->syncTable($name);
        }
    }

    protected function syncTable($name){

        $time = date('Y-m-d H:i:s.u');
        $this->info("Syncronizing table ".$name." at $time ");

        $className = ucfirst(camel_case($name));
        
        $ourModelName = '\App\Xml\\'.$className;

        $ourModel = new $ourModelName;
        $this->syncChanged($ourModel,$name);

        // $this->info("---".$name."----");
    }

    protected function syncChanged($ourModel,$name){
        if(Storage::exists('xml/'.$name.'.XML')) {
            $path = Storage::get('xml/'.$name.'.XML');
            $xml = simplexml_load_string($path);
            $json = json_encode($xml);
            $array = json_decode($json);
            if (!empty($array)) {
                
                if(config('sfa.xml_item') === $name){
                    $this->info("Fetched ".count((array)$array->SALES_PARTS->SALES_PART)." rows from oracle connection.");
                    try {
                        DB::beginTransaction();
                        $updated = 0;
                        foreach($array->SALES_PARTS->SALES_PART AS $key=>$item){
                            $where = [
                                'catalog_no'=>$item->CATALOG_NO
                            ];
                            $exists = $ourModel::where($where)->latest()->first();

                            $data = [
                                'contract'=>(!is_object($item->CONTRACT))?$item->CONTRACT:NULL,
                                'catalog_no'=>(!is_object($item->CATALOG_NO))?$item->CATALOG_NO:NULL,
                                'catalog_desc'=>(!is_object($item->CATALOG_DESC))?$item->CATALOG_DESC:NULL,
                                'part_main_group'=>(!is_object($item->PART_MAIN_GROUP))?$item->PART_MAIN_GROUP:NULL,
                                'part_main_group_desc'=>(!is_object($item->PART_MAIN_GROUP_DESC))?$item->PART_MAIN_GROUP_DESC:NULL,
                                'style_no'=>(!is_object($item->STYLE_NO))?$item->STYLE_NO:NULL,
                                'style_desc'=>(!is_object($item->STYLE_DESC))?$item->STYLE_DESC:NULL,
                                'catalog_group'=>(!is_object($item->STYLE_DESC))?$item->STYLE_DESC:NULL,
                                'catalog_group_desc'=>(!is_object($item->CATALOG_GROUP_DESC))?$item->CATALOG_GROUP_DESC:NULL,
                                'sub_category'=>(!is_object($item->SUB_CATEGORY))?$item->SUB_CATEGORY:NULL,
                                'sub_category_desc'=>(!is_object($item->SUB_CATEGORY_DESC))?$item->SUB_CATEGORY_DESC:NULL,
                                'category'=>(!is_object($item->CATEGORY))?$item->CATEGORY:NULL,
                                'category_desc'=>(!is_object($item->CATEGORY_DESC))?$item->CATEGORY_DESC:NULL,
                                'brand'=>(!is_object($item->BRAND))?$item->BRAND:NULL,
                                'brand_desc'=>(!is_object($item->BRAND_DESC))?$item->BRAND_DESC:NULL,
                                'supplier_article_no'=>(!is_object($item->SUPPLIER_ARTICLE_NO))?$item->SUPPLIER_ARTICLE_NO:NULL,
                                'part_type'=>(!is_object($item->PART_TYPE))?$item->PART_TYPE:NULL,
                                'unit'=>(!is_object($item->UNIT))?$item->UNIT:NULL,
                                'vat_code'=>(!is_object($item->VAT_CODE))?$item->VAT_CODE:NULL,
                                'price_net'=>(!is_object($item->PRICE_NET))?$item->PRICE_NET:NULL,
                                'vat'=>(!is_object($item->VAT))?$item->VAT:NULL,
                                'price'=>(!is_object($item->PRICE))?$item->PRICE:NULL,
                                'active'=>(!is_object($item->ACTIVE))?$item->ACTIVE:NULL,
                                'currency_code'=>(!is_object($item->CURRENCY_CODE))?$item->CURRENCY_CODE:NULL,
                                'sales_start'=>(!is_object($item->SALES_START))?$item->SALES_START:NULL,

                                'block_for_sales'=>(!is_object($item->BLOCK_FOR_SALES))?$item->BLOCK_FOR_SALES:NULL,
                                'block_for_sales_return'=>(!is_object($item->BLOCK_FOR_SALES_RETURN))?$item->BLOCK_FOR_SALES_RETURN:NULL
                            ];

                            if($key%1000==0) $this->info("Finished ".($key+1)." rows.");

                            if ($exists) {
                                $updated++;

                                $exists = $ourModel::where($where)->update($data);
                            } else {
                                $exists = $ourModel::create($data);
                            }
                        }
                        $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");

                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollback();
                        $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                    }
                }

                if(config('sfa.xml_price_list_dealer') == $name){
                    $this->info("Fetched ".count((array)$array->PRICE_LISTS->PRICE_LIST[0]->PRICE_LIST_INFO_LINES->PRICE_LIST_INFO_LINE)." rows from oracle connection.");

                    try {
                        DB::beginTransaction();

                        $updated = 0;
                        $pro_count = 0;
                        foreach($array->PRICE_LISTS->PRICE_LIST as $key=>$list){
                            foreach($array->PRICE_LISTS->PRICE_LIST[$key]->PRICE_LIST_INFO_LINES->PRICE_LIST_INFO_LINE as $listItm){
                                if(is_object($listItm) && count((array)$listItm)>0){
                                    $where = [
                                        'price_list_no'=>$list->PRICE_LIST_NO,
                                        'catalog_no'=>$listItm->CATALOG_NO,
                                        'valid_from'=>$listItm->VALID_FROM
                                    ];
                                    $exists = $ourModel::where($where)->latest()->first();
                                    
                                    $data = [
                                        'currency_code'=>(!is_object($list->CURRENCY_CODE))?$list->CURRENCY_CODE:NULL,
                                        'price_list_no'=>(!is_object($list->PRICE_LIST_NO))?$list->PRICE_LIST_NO:NULL,
                                        'price_list_description'=>(!is_object($list->PRICE_LIST_DESCRIPTION))?$list->PRICE_LIST_DESCRIPTION:NULL,
                                        'catalog_no'=>(!is_object($listItm->CATALOG_NO))?$listItm->CATALOG_NO:NULL,
                                        'valid_from'=>(!is_object($listItm->VALID_FROM))?$listItm->VALID_FROM:NULL,
                                        'valid_to'=>(!is_object($listItm->VALID_TO))?$listItm->VALID_TO:NULL,
                                        'price_net'=>(!is_object($listItm->PRICE_NET))?$listItm->PRICE_NET:NULL,
                                        'vat'=>(!is_object($listItm->VAT))?$listItm->VAT:NULL,
                                        'price'=>(!is_object($listItm->PRICE))?$listItm->PRICE:NULL,
                                        'discount_type'=>(!is_object($listItm->DISCOUNT_TYPE))?$listItm->DISCOUNT_TYPE:NULL,
                                        'discount_type_desc'=>(!is_object($listItm->DISCOUNT_TYPE_DESC))?$listItm->DISCOUNT_TYPE_DESC:NULL,
                                        'discount'=>(!is_object($listItm->DISCOUNT))?$listItm->DISCOUNT:NULL,
                                    ];

                                    if($pro_count%1000==0) $this->info("Finished ".($pro_count+1)." rows.");
                                    $pro_count++;
                                    if ($exists) {
                                        $updated++;
                                        $exists = $ourModel::where($where)->update($data);
                                    } else {
                                        $exists = $ourModel::create($data);
                                    }
                                }
                            }
                        }
                        $this->info("Changes affected to ".($pro_count)." row(s). ".($updated)." rows updated. ".($pro_count-$updated)." rows newly created");

                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollback();
                        $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                    }
                }

                if(config('sfa.xml_customer') == $name){
                    $this->info("Fetched ".count((array)$array->CUSTOMERS->CUSTOMER)." rows from oracle connection.");

                    try {
                        DB::beginTransaction();
                        $updated = 0;
                        foreach($array->CUSTOMERS->CUSTOMER AS $key=>$item){
                            $where = [
                                'customer_id'=>$item->CUSTOMER_ID
                            ];
                            $exists = $ourModel::where($where)->latest()->first();

                            $data = [
                                'customer_id'=>(!is_object($item->CUSTOMER_ID))?$item->CUSTOMER_ID:NULL,
                                'name'=>(!is_object($item->NAME))?$item->NAME:NULL,
                                'association_no'=>(!is_object($item->ASSOCIATION_NO))?$item->ASSOCIATION_NO:NULL,
                                'reference'=>(!is_object($item->REFERENCE))?$item->REFERENCE:NULL,
                                'cust_price_group_id'=>(!is_object($item->CUST_PRICE_GROUP_ID[0]))?$item->CUST_PRICE_GROUP_ID[0]:NULL,
                                'cust_price_group_desc'=>(!is_object($item->CUST_PRICE_GROUP_DESC))?$item->CUST_PRICE_GROUP_DESC:NULL,
                                'cust_category1_id'=>(!is_object($item->CUST_CATEGORY1_ID))?$item->CUST_CATEGORY1_ID:NULL,
                                'cust_category1_desc'=>(!is_object($item->CUST_CATEGORY1_DESC))?$item->CUST_CATEGORY1_DESC:NULL,
                                'cust_category2_id'=>(!is_object($item->CUST_CATEGORY2_ID))?$item->CUST_CATEGORY2_ID:NULL,
                                'cust_category2_desc'=>(!is_object($item->CUST_CATEGORY2_DESC))?$item->CUST_CATEGORY2_DESC:NULL,
                                'cust_district_id'=>(!is_object($item->CUST_DISTRICT_ID))?$item->CUST_DISTRICT_ID:NULL,
                                'cust_district_desc'=>(!is_object($item->CUST_DISTRICT_DESC))?$item->CUST_DISTRICT_DESC:NULL,
                                'cust_town_id'=>(!is_object($item->CUST_TOWN_ID))?$item->CUST_TOWN_ID:NULL,
                                'cust_town_desc'=>(!is_object($item->CUST_TOWN_DESC))?$item->CUST_TOWN_DESC:NULL,
                                'cust_region_id'=>(!is_object($item->CUST_REGION_ID))?$item->CUST_REGION_ID:NULL,
                                'cust_region_desc'=>(!is_object($item->CUST_REGION_DESC))?$item->CUST_REGION_DESC:NULL,
                                'cust_route_id'=>(!is_object($item->CUST_ROUTE_ID))?$item->CUST_ROUTE_ID:NULL,
                                'cust_route_desc'=>(!is_object($item->CUST_ROUTE_DESC))?$item->CUST_ROUTE_DESC:NULL,
                                'cust_business_sec_id'=>(!is_object($item->CUST_BUSINESS_SEC_ID))?$item->CUST_BUSINESS_SEC_ID:NULL,
                                'cust_business_sec_desc'=>(!is_object($item->CUST_BUSINESS_SEC_DESC))?$item->CUST_BUSINESS_SEC_DESC:NULL,
                                'cust_main_channel_id'=>(!is_object($item->CUST_MAIN_CHANNEL_ID))?$item->CUST_MAIN_CHANNEL_ID:NULL,
                                'cust_main_channel_desc'=>(!is_object($item->CUST_MAIN_CHANNEL_DESC))?$item->CUST_MAIN_CHANNEL_DESC:NULL,
                                'cust_sub_channel_id'=>(!is_object($item->CUST_SUB_CHANNEL_ID))?$item->CUST_SUB_CHANNEL_ID:NULL,
                                'cust_sub_channel_desc'=>(!is_object($item->CUST_SUB_CHANNEL_DESC))?$item->CUST_SUB_CHANNEL_DESC:NULL,
                                'credit_blocked'=>(!is_object($item->CREDIT_BLOCKED))?$item->CREDIT_BLOCKED:NULL,
                                'credit_limit'=>(!is_object($item->CREDIT_LIMIT))?$item->CREDIT_LIMIT:NULL,
                                'credit_blocked_open_invoice'=>(!is_object($item->CREDIT_BLOCKED_OPEN_INVOICE))?$item->CREDIT_BLOCKED_OPEN_INVOICE:NULL,
                                'payment_term_id'=>(!is_object($item->PAYMENT_TERM_ID))?$item->PAYMENT_TERM_ID:NULL,
                                'payment_term_description'=>(!is_object($item->PAYMENT_TERM_DESCRIPTION))?$item->PAYMENT_TERM_DESCRIPTION:NULL,
                                'payment_term_no_of_days'=>(!is_object($item->PAYMENT_TERM_NO_OF_DAYS))?$item->PAYMENT_TERM_NO_OF_DAYS:NULL,
                                'currency_code'=>(!is_object($item->CURRENCY_CODE))?$item->CURRENCY_CODE:NULL,
                                'vat_no'=>(!is_object($item->VAT_NO))?$item->VAT_NO:NULL,
                                'active'=>(!is_object($item->ACTIVE))?$item->ACTIVE:NULL,
                                'address_id'=>(!is_object($item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->ADDRESS_ID))?$item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->ADDRESS_ID:NULL,
                                'address_1'=>(!is_object($item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->ADDRESS_1))?$item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->ADDRESS_1:NULL,
                                'address_2'=>(!is_object($item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->ADDRESS_2))?$item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->ADDRESS_2:NULL,
                                'zip_code'=>(!is_object($item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->ZIP_CODE))?$item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->ZIP_CODE:NULL,
                                'city'=>(!is_object($item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->CITY))?$item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->CITY:NULL,
                                'county'=>(!is_object($item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->COUNTY))?$item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->COUNTY:NULL,
                                'state'=>(!is_object($item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->STATE))?$item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->STATE:NULL,
                                'country'=>(!is_object($item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->COUNTRY))?$item->CUSTOMER_ADDRESSES->CUSTOMER_DOC_ADDRESS->COUNTRY:NULL,
                                'phone'=>(is_object($item->PHONE_NOS) && isset($item->PHONE_NOS->PHONE_NO) && isset($item->PHONE_NOS->PHONE_NO->PHONE))? $item->PHONE_NOS->PHONE_NO->PHONE:NULL,
                                'mobile'=>(is_object($item->MOBILE_NOS) && isset($item->MOBILE_NOS->MOBILE_NO) && isset($item->MOBILE_NOS->MOBILE_NO->MOBILE))? $item->MOBILE_NOS->MOBILE_NO->MOBILE:NULL,
                                'email'=>(is_object($item->EMAILS) && isset($item->EMAILS->EMAIL) && isset($item->EMAILS->EMAIL->EMAIL))? $item->EMAILS->EMAIL->EMAIL:NULL,
                                'contract'=>(is_object($item->CUSTOMER_VALID_SITES) && isset($item->CUSTOMER_VALID_SITES->CUSTOMER_VALID_SITE) && isset($item->CUSTOMER_VALID_SITES->CUSTOMER_VALID_SITE->CONTRACT))? $item->CUSTOMER_VALID_SITES->CUSTOMER_VALID_SITE->CONTRACT:NULL,
                                'default'=>(is_object($item->CUSTOMER_VALID_SITES) && isset($item->CUSTOMER_VALID_SITES->CUSTOMER_VALID_SITE) && isset($item->CUSTOMER_VALID_SITES->CUSTOMER_VALID_SITE->DEFAULT))? $item->CUSTOMER_VALID_SITES->CUSTOMER_VALID_SITE->DEFAULT:NULL,
                                'bank_acc_id'=>(is_object($item->CUSTOMER_BANK_ACC) && isset($item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS) && is_array($item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS))?$item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS[0]->BANK_ACC_ID:NULL,
                                'account_code'=>(is_object($item->CUSTOMER_BANK_ACC) && isset($item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS) && is_array($item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS))?$item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS[0]->ACCOUNT_CODE:NULL,
                                'bank_acc_status'=>(is_object($item->CUSTOMER_BANK_ACC) && isset($item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS) && is_array($item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS))?$item->CUSTOMER_BANK_ACC->BANK_ACCOUNTS[0]->STATUS:NULL,
                            ];

                            if($key%1000==0) $this->info("Finished ".($key+1)." rows.");

                            if ($exists) {
                                $updated++;

                                $exists = $ourModel::where($where)->update($data);
                            } else {
                                $exists = $ourModel::create($data);
                            }
                        }
                        $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                        
                        DB::commit();
                    } catch (\Exception $exception) {
                        DB::rollback();
                        $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                    }
                }
                    /* 
                    customer price group
                    cols: customer price group,group description,price list no, list description
                    */

                    if(config('sfa.xml_customer_price_group') === $name){
                        $this->info("Fetched");
                        $this->info("Fetched ".count((array)$array->CUSTOMER_PRICE_GROUPS->CUSTOMER_PRICE_GROUP->PRICE_LISTS)." rows from oracle connection.");
                        try{
                            DB::beginTransaction();
                            $updated = 0;
                            foreach((array)$array->CUSTOMER_PRICE_GROUPS  AS $key=>$item){
                                foreach($item->PRICE_LISTS AS $item1){
                                    
                                    foreach($item1 as $ky=>$ww){
                                        $where = [
                                            'cust_price_group_id'=> $item->CUST_PRICE_GROUP_ID,
                                            'price_list_no'=> $ww->PRICE_LIST_NO[0]
                                        ];

                                        $data = [
                                            'cust_price_group_id'=> $item->CUST_PRICE_GROUP_ID,
                                            'cust_price_group_description'=> $item->DESCRIPTION,
                                            'price_list_no'=> $ww->PRICE_LIST_NO[0],
                                            'price_list_description'=> $ww->PRICE_LIST_NO[1]
                                        ];

                                        $exists = $ourModel::where($where)->latest()->first();
                                    
                                        if($ky%1000==0) $this->info("Finished ".($ky+1)." rows.");
                                        if ($exists) {
                                            $updated++;
            
                                            $exists = $ourModel::where($where)->update($data);
                                        } else {
                                            $exists = $ourModel::create($data);
                                        }
                                    }
                                }
                            }


                            DB::commit();


                        } catch (\Exception $exception) {
                            DB::rollback();
                            $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                        }
                        
                    }
                    
                    //sales reps
                    if(config('sfa.xml_sales_rep') === $name){
                        $this->info("Fetched ".count((array)$array->SALES_REPS->SALES_REP)." rows from oracle connection.");
                        
                            try {
                                DB::beginTransaction();
                                $updated = 0;
                                foreach((array)$array->SALES_REPS->SALES_REP AS $key=>$item){


                                    $where = [
                                        'sales_rep_id'=>$item->SALES_REP_ID
                                    ];
                                    $exists = $ourModel::where($where)->latest()->first();
        
                                    $data = [
                                        'sales_rep_id'=>(!is_object($item->SALES_REP_ID))?$item->SALES_REP_ID:NULL,
                                        'sales_rep_name'=>(!is_object($item->SALES_REP_NAME))?$item->SALES_REP_NAME:NULL,
                                        'phone_no'=>(!is_object($item->PHONE_NO))?$item->PHONE_NO:NULL,
                                        'email'=>(!is_object($item->EMAIL))?$item->EMAIL:NULL,
                                        'active_site'=>(!is_object($item->ACTIVE_SITE))?$item->ACTIVE_SITE:NULL,
                                        'valid_from_date'=>(!is_object($item->VALID_FROM_DATE))?$item->VALID_FROM_DATE:NULL,
                                        'block_for_use'=>(!is_object($item->BLOCK_FOR_USE))?$item->BLOCK_FOR_USE:NULL,
                                        ];
        
                                    if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
        
                                    if ($exists) {
                                        $updated++;
        
                                        $exists = $ourModel::where($where)->update($data);
                                    } else {
                                        $exists = $ourModel::create($data);
                                    }
                                }
                                 $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                DB::commit();

                                    } catch (\Exception $exception){
                                        DB::rollback();
                                        $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                                    }
                             }


                             //tm rsm
                    if(config('sfa.xml_tm_rsm') === $name){

                        try {
                            DB::beginTransaction();
                            $updated = 0;
                                foreach((array)$array->TM_RSMS->TM_RSM AS $key=>$item){

                                   foreach($item->SITES AS $k=>$site){
                                    foreach($site AS $ky=>$sites){
                                        if(isset($sites->CONTRACT)){
                          
                                                $where = [
                                                    'person_id'=>$item->PERSON_ID,
                                                    'contract'=>$sites->CONTRACT
                                                ];
                                                $exists = $ourModel::where($where)->latest()->first();
                    
                                                $data = [
                                                    'person_id'=>(!is_object($item->PERSON_ID))?$item->PERSON_ID:NULL,
                                                    'person_name'=>(!is_object($item->PERSON_NAME))?$item->PERSON_NAME:NULL,
                                                    'level_id'=>(!is_object($item->LEVEL_ID))?$item->LEVEL_ID:NULL,
                                                    'level_name'=>(!is_object($item->LEVEL_NAME))?$item->LEVEL_NAME:NULL,
                                                    'mobile'=>(!is_object($item->MOBILE))?$item->MOBILE:NULL,
                                                    'email'=>(!is_object($item->EMAIL))?$item->EMAIL:NULL,
                                                    'status'=>(!is_object($item->STATUS))?$item->STATUS:NULL,
                                                    'contract'=>(!is_object($sites->CONTRACT))?$sites->CONTRACT:NULL,
                                                    ];
                    
                                                if($ky%1000==0) $this->info("Finished ".($ky+1)." rows.");
                    
                                                if ($exists) {
                                                    $updated++;
                    
                                                    $exists = $ourModel::where($where)->update($data);
                                                } else {
                                                    $exists = $ourModel::create($data);
                                                }
                                            
                                             
                                            
                                    }
                                    }
                                   }
                                }

                                // $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                             DB::commit();

                            } catch (\Exception $exception){
                                DB::rollback();
                                $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                            }
                                
                             }

                               //tm rsm
                    if(config('sfa.xml_customer_outstanding_invoice') === $name){

                        try {
                            DB::beginTransaction();
                            $updated = 0;
                                foreach((array)$array->CUSTOMERS->CUSTOMER AS $key=>$item){
        
                                                $where = [
                                                    'customer_id'=>$item->CUSTOMER_ID
                                                ];
                                                $exists = $ourModel::where($where)->latest()->first();
                    
                                                $data = [
                                                    'customer_id'=>(!is_object($item->CUSTOMER_ID))?$item->CUSTOMER_ID:NULL,
                                                    'name'=>(!is_object($item->NAME))?$item->NAME:NULL,
                                                    'credit_limit'=>(!is_object($item->CREDIT_LIMIT))?$item->CREDIT_LIMIT:NULL,
                                                    'credit_blocked'=>(!is_object($item->CREDIT_BLOCKED))?$item->CREDIT_BLOCKED:NULL,
                                                    'credit_blocked_open_invoice'=>(!is_object($item->CREDIT_BLOCKED_OPEN_INVOICE))?$item->CREDIT_BLOCKED_OPEN_INVOICE:NULL,
                                                    ];

                                                    //inserting invoices
                                                   
                                                        foreach($item->INVOICES AS $inv){
                                                            if(isset($inv->SITE_NO)){

                                                                $wherePart = [
                                                                    'customer_id'=>$inv->SITE_NO,
                                                                    'invoice_no'=>$inv->INVOICE_NO,
                                                                ];
                                                                $dataPart = [
                                                                    'customer_id'=>$item->CUSTOMER_ID,
                                                                    'site_no'=>$inv->SITE_NO,
                                                                    'series_id'=>$inv->SERIES_ID,
                                                                    'invoice_no'=>$inv->INVOICE_NO,
                                                                    'invoice_date'=>$inv->INVOICE_DATE,
                                                                    'due_date'=>$inv->DUE_DATE,
                                                                    'invoice_amount'=>$inv->INVOICE_AMOUNT,
                                                                    'open_amount'=>$inv->OPEN_AMOUNT
                                                                ];
                                                                $existsPart = CustomerOutstandingInvoiceList::where($wherePart)->latest()->first();
                                                                if ($existsPart) {
                                                                    $existsPart = CustomerOutstandingInvoiceList::where($wherePart)->update($dataPart);
                                                                } else {
                                                                    $existsPart = CustomerOutstandingInvoiceList::create($dataPart);
                                                                }


                                                               
                                                            }
                                                            
    
    
    
                                                        }
                                                    
                                                    

                                                    //inserting invoices end
                    
                                                if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
                    
                                                if ($exists) {
                                                    $updated++;
                    
                                                    $exists = $ourModel::where($where)->update($data);
                                                } else {
                                                    $exists = $ourModel::create($data);
                                                }
                                            
                                             
                                            
                                    }

                                $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                             DB::commit();

                            } catch (\Exception $exception){
                                DB::rollback();
                                $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                            }
                                
                             }

                             //cunstomer bank details

                             if(config('sfa.xml_customer_bank_details') === $name){
                                $this->info("Fetched ".count((array)$array->BANK_CODES)." rows from oracle connection.");
                                try {
                                    DB::beginTransaction();
                                    $updated = 0;
                                    $key=0;
                                        foreach((array)$array->BANK_CODES AS $item){
                                            $key++;
                                                        $where = [
                                                            'code'=>$item->CODE
                                                        ];
                                                        $exists = $ourModel::where($where)->latest()->first();
                            
                                                        $data = [
                                                            'code'=>(!is_object($item->CODE))?$item->CODE:NULL,
                                                            'description'=>(!is_object($item->DESCRIPTION))?$item->DESCRIPTION:NULL,
                                                                                                                        ];
                            
                                                        if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
                            
                                                        if ($exists) {
                                                            $updated++;
                            
                                                            $exists = $ourModel::where($where)->update($data);
                                                        } else {
                                                            $exists = $ourModel::create($data);
                                                        }
                                                    
                                                     
                                                    
                                            }
        
                                        $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                                     DB::commit();
        
                                    } catch (\Exception $exception){
                                        DB::rollback();
                                        $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                                    }
                                        
                                     }


                                    //  discount volume

                                    if(config('sfa.xml_volume_discount') === $name){
                                        $this->info("Fetched ".count((array)$array->BANK_CODES)." rows from oracle connection.");
                                        try {
                                            DB::beginTransaction();
                                            $updated = 0;
                                            $key=0;
                                                foreach((array)$array->BANK_CODES AS $item){
                                                    $key++;
                                                                $where = [
                                                                    'code'=>$item->CODE
                                                                ];
                                                                $exists = $ourModel::where($where)->latest()->first();
                                    
                                                                $data = [
                                                                    'code'=>(!is_object($item->CODE))?$item->CODE:NULL,
                                                                    'description'=>(!is_object($item->DESCRIPTION))?$item->DESCRIPTION:NULL,
                                                                                                                                ];
                                    
                                                                if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
                                    
                                                                if ($exists) {
                                                                    $updated++;
                                    
                                                                    $exists = $ourModel::where($where)->update($data);
                                                                } else {
                                                                    $exists = $ourModel::create($data);
                                                                }
                                                            
                                                             
                                                            
                                                    }
                
                                                $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                                             DB::commit();
                
                                            } catch (\Exception $exception){
                                                DB::rollback();
                                                $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                                            }
                                                
                                             }


                                             //xml_stock_on_hand

                    if(config('sfa.xml_stock_on_hand') === $name){
                            $this->info("Fetched ".count((array)$array->INVENTORY_PARTS->INVENTORY_PART)." rows from oracle connection.");
                            try {
                                DB::beginTransaction();
                                $updated = 0;
                                $key=0;
                                    foreach((array)$array->INVENTORY_PARTS->INVENTORY_PART AS $key=>$item){
                                        foreach($item->LOCATIONS AS $ky=>$itm){
                                            $key++;
                                            $where = [
                                                'part_no'=>$item->PART_NO,
                                                'contract'=>$item->CONTRACT,
                                            ];
                                            $exists = $ourModel::where($where)->latest()->first();
                                                if(isset($itm->LOCATION_NO)){
                                                    $data = [
                                                        'part_no'=>(!is_object($item->PART_NO))?$item->PART_NO:NULL,
                                                        'contract'=>(!is_object($item->CONTRACT))?$item->CONTRACT:NULL,
                                                        'location_no'=>(!is_object($itm->LOCATION_NO))?$itm->LOCATION_NO:NULL,
                                                        'qty'=>(!is_object($itm->QTY))?$itm->QTY:NULL,
                                                        'open_order_qty'=>(!is_object($item->OPEN_ORDER_QTY))?$item->OPEN_ORDER_QTY:NULL,
                                                            ];
                        
                                                    if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
                        
                                                    if ($exists) {
                                                        $updated++;
                        
                                                        $exists = $ourModel::where($where)->update($data);
                                                    } else {
                                                        $exists = $ourModel::create($data);
                                                    } 
                                                }
                                           
                                        }
                                        }
    
                                    $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                                    DB::commit();
    
                                } catch (\Exception $exception){
                                    DB::rollback();
                                    $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                                }
                                                        
                    }

                    if(config('sfa.xml_vat_details') === $name){
                        $this->info("Fetched ".count((array)$array->VAT_CODES->VAT_CODE)." rows from oracle connection.");
                        try {
                            DB::beginTransaction();
                            $updated = 0;
                            $key=0;
                                foreach((array)$array->VAT_CODES->VAT_CODE AS $key=>$item){
                                    
                                        $key++;
                                        $where = [
                                            'code'=>$item->CODE
                                        ];
                                        $exists = $ourModel::where($where)->latest()->first();
                                            
                                                $data = [
                                                    'code'=>(!is_object($item->CODE))?$item->CODE:NULL,
                                                    'description'=>(!is_object($item->DESCRIPTION))?$item->DESCRIPTION:NULL,
                                                    'vat_rate'=>(!is_object($item->VAT_RATE))?$item->VAT_RATE:NULL,
                                                    'valid_from'=>(!is_object($item->VALID_FROM))?$item->VALID_FROM:NULL,
                                                    'valid_to'=>(!is_object($item->VALID_TO))?$item->VALID_TO:NULL,
                                                        ];
                    
                                                if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
                    
                                                if ($exists) {
                                                    $updated++;
                    
                                                    $exists = $ourModel::where($where)->update($data);
                                                } else {
                                                    $exists = $ourModel::create($data);
                                                } 
                                            
                                       
                                    
                                    }

                                $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                                DB::commit();

                            } catch (\Exception $exception){
                                DB::rollback();
                                $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                            }
                                                    
                }

                if(config('sfa.xml_return_reason') === $name){
                    $this->info("Fetched ".count((array)$array->RETURN_REASONS)." rows from oracle connection.");
                    try {
                        DB::beginTransaction();
                        $updated = 0;
                        $key=0;
                            foreach((array)$array->RETURN_REASONS AS $item){
                                    $key++;
                                    $where = [
                                        'return_reason_code'=>$item->RETURN_REASON_CODE
                                    ];
                                    $exists = $ourModel::where($where)->latest()->first();
                                        
                                            $data = [
                                                'return_reason_code'=>(!is_object($item->RETURN_REASON_CODE))?$item->RETURN_REASON_CODE:NULL,
                                                'return_reason_description'=>(!is_object($item->RETURN_REASON_DESCRIPTION))?$item->RETURN_REASON_DESCRIPTION:NULL,
                                                    ];
                
                                            if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
                
                                            if ($exists) {
                                                $updated++;
                
                                                $exists = $ourModel::where($where)->update($data);
                                            } else {
                                                $exists = $ourModel::create($data);
                                            } 
                                }

                            $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                            DB::commit();

                        } catch (\Exception $exception){
                            DB::rollback();
                            $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                        }
                                                
            }

            if(config('sfa.xml_sales_return_block_item') === $name){
                $this->info("Fetched ".count((array)$array->SITE_CLUSTERS->SITE_CLUSTER)." rows from oracle connection.");
                    try {
                        DB::beginTransaction();
                        $updated = 0;
                            foreach((array)$array->SITE_CLUSTERS->SITE_CLUSTER AS $key=>$item){
                                    
                                    $where = [
                                        'site_cluster_id'=>$item->SITE_CLUSTER_ID
                                    ];
                                    $exists = $ourModel::where($where)->latest()->first();
                                        
                                            $data = [
                                                'site_cluster_id'=>(!is_object($item->SITE_CLUSTER_ID))?$item->SITE_CLUSTER_ID:NULL,
                                                'site_cluster_desc'=>(!is_object($item->SITE_CLUSTER_DESC))?$item->SITE_CLUSTER_DESC:NULL,
                                                    ];
                                                    if(isset($item->SITES->SITE)){

                                                        foreach($item->SITES->SITE AS $item_sites){
                                                            $whereSite = [
                                                                'site_cluster_id'=>$item->SITE_CLUSTER_ID,
                                                                'contract'=>$item_sites->CONTRACT
                                                            ];
                                                            $dateSite = [
                                                                'site_cluster_id'=>$item->SITE_CLUSTER_ID,
                                                                'contract'=>$item_sites->CONTRACT
                                                            ];
                                                            $existsSite = SalesReturnBlockItemSites::where($whereSite)->latest()->first();
                                                            if ($existsSite) {
                                                                $existsSite = SalesReturnBlockItemSites::where($whereSite)->update($dateSite);
                                                            } else {
                                                                $existsSite = SalesReturnBlockItemSites::create($dateSite);
                                                            } 
                                                            
                                                         }
                                                    }
                                                    
                                                    if(isset($item->PARTS->PART)){
                                                    foreach($item->PARTS->PART AS $item_parts){
                                                        $wherePart = [
                                                            'site_cluster_id'=>$item->SITE_CLUSTER_ID,
                                                            'contract'=>$item_parts->CONTRACT
                                                        ];
                                                        $dataPart = [
                                                            'site_cluster_id'=>$item->SITE_CLUSTER_ID,
                                                            'contract'=>$item_parts->CONTRACT
                                                        ];
                                                        $existsPart = SalesReturnBlockItemParts::where($wherePart)->latest()->first();
                                                        if ($existsPart) {
                                                            $existsPart = SalesReturnBlockItemParts::where($wherePart)->update($dataPart);
                                                        } else {
                                                            $existsPart = SalesReturnBlockItemParts::create($dataPart);
                                                        }
                                                    
                                                    }
                                                    }

                
                                            if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
                
                                            if ($exists) {
                                                $updated++;
                
                                                $exists = $ourModel::where($where)->update($data);
                                            } else {
                                                $exists = $ourModel::create($data);
                                            } 

                                }

                            $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                            DB::commit();

                        } catch (\Exception $exception){
                            DB::rollback();
                            $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                        }
            }


            if(config('sfa.xml_site_location') === $name){
                
                $this->info("Fetched ".count((array)$array->SITES->SITE)." rows from oracle connection.");
                try {
                    DB::beginTransaction();
                    $updated = 0;
                    $key=0;
                        foreach((array)$array->SITES->SITE AS $item){
                            foreach($item->LOCATIONS->LOCATION AS $itm){
                                $key++;
                                $where = [
                                    'contract'=>$item->CONTRACT,
                                    'location_no'=>$itm->LOCATION_NO
                                ];
                                $exists = $ourModel::where($where)->latest()->first();
                                    
                                        $data = [
                                            'contract'=>(!is_object($item->CONTRACT))?$item->CONTRACT:NULL,
                                            'name'=>(!is_object($item->NAME))?$item->NAME:NULL,
                                            'address1'=>(!is_object($item->ADDRESS1))?$item->ADDRESS1:NULL,
                                            'address2'=>(!is_object($item->ADDRESS2))?$item->ADDRESS2:NULL,
                                            'zip_code'=>(!is_object($item->ZIP_CODE))?$item->ZIP_CODE:NULL,
                                            'city'=>(!is_object($item->CITY))?$item->CITY:NULL,
                                            'state'=>(!is_object($item->STATE))?$item->STATE:NULL,
                                            'country'=>(!is_object($item->COUNTRY))?$item->COUNTRY:NULL,
                                            'location_no'=>(!is_object($itm->LOCATION_NO))?$itm->LOCATION_NO:NULL,
                                            'location_group'=>(!is_object($itm->LOCATION_GROUP))?$itm->LOCATION_GROUP:NULL,
                                            'location_group_desc'=>(!is_object($itm->LOCATION_GROUP_DESC))?$itm->LOCATION_GROUP_DESC:NULL,
                                                ];
            
                                        if($key%1000==0) $this->info("Finished ".($key+1)." rows.");
            
                                        if ($exists) {
                                            $updated++;
            
                                            $exists = $ourModel::where($where)->update($data);
                                        } else {
                                            $exists = $ourModel::create($data);
                                        } 

                            }
                               
                        }

                        $this->info("Changes affected to ".($key+1)." row(s). ".($updated)." rows updated. ".($key+1-$updated)." rows newly created");
                                        DB::commit();

                    } catch (\Exception $exception){
                        DB::rollback();
                        $this->error("Failed to write to $name table. Error:- ".$exception->__toString());
                    }


            }

            }
        }
        
    }
}
