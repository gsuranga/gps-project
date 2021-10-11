<?php

namespace App\Xml;

use App\Models\Base;

class VatDetails extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_vat_details';
    protected $primaryKey = 'ifs_vat_id';

    protected $fillable = [
        'code',
        'description',
        'vat_rate',
        'valid_from',
        'valid_to'
    ];
}
