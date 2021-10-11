<?php

namespace App\Xml;

use App\Models\Base;

class CustomerOutstandingInvoiceList extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_customer_outstanding_invoice_lists';
    protected $primaryKey = 'ifs_outstanding_invoice_list_id';

    protected $fillable = [
        'customer_id',
        'site_no',
        'series_id',
        'invoice_no',
        'sfa_inv_no',
        'currency_code',
        'invoice_date',
        'due_date',
        'invoice_amount',
        'open_amount'
    ];
}
