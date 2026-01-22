<?php

namespace Modules\Vendor\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Vendor\Models\VendorTeam;

class VendorTeamRequestCreatedEvent
{
    use Dispatchable, SerializesModels;

    public $vendor_team;

    public function __construct(VendorTeam $vendorTeam)
    {
        $this->vendor_team = $vendorTeam;
    }
}
