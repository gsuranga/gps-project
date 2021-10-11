<?php

namespace App\Xml;

use App\Models\Base;

class ReturnReason extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_return_reasons';
    protected $primaryKey = 'ifs_return_reason_id';

    protected $fillable = [
        'return_reason_code',
        'return_reason_description',
    ]; 
}
