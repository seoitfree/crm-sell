<?php

namespace CrmSell\Orders\Domains\Entities;


use CrmSell\Common\Domains\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use UuidTrait;

    protected $table = 'shipments';
}
