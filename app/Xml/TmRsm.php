<?php

namespace App\Xml;

use App\Models\Base;

class TmRsm extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_tm_rsms';
    protected $primaryKey = 'ifs_tm_rsms_id';

    protected $fillable = [
        'person_id',
        'person_name',
        'level_id',
        'level_name',
        'mobile',
        'email',
        'status',
        'contract'
    ];


}
