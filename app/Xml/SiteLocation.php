<?php

namespace App\Xml;

use App\Models\Base;

class SiteLocation extends Base
{
    protected $revisionEnabled = true;
    protected $table = 'ifs_site_locations';
    protected $primaryKey = 'ifs_site_location_id';

    protected $fillable = [
        'ifs_site_location_id',
        'contract',
        'name',
        'address1',
        'address2',
        'zip_code',
        'city',
        'state',
        'country',
        'location_no',
        'location_group',
        'location_group_desc',
    ];
}
