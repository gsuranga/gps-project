<?php

namespace App\Xml;

use App\Models\Base;

class CustomerOutstandingInvoice extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_customer_outstanding_invoices';
    protected $primaryKey = 'ifs_outstanding_invoices_id';

    protected $fillable = [
        'customer_id',
        'name',
        'credit_limit',
        'credit_blocked',
        'open_invoice',
        'credit_blocked_open_invoice',
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
