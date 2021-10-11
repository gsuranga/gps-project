<?php

namespace App\Xml;

use App\Models\Base;

class CustomerBankDetails extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_customer_bank_details';
    protected $primaryKey = 'ifs_bank_id';

    protected $fillable = [
        'code',
        'description'
    ];
}
