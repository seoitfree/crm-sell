<?php

namespace CrmSell\Orders\Domains\Entities;


use CrmSell\Common\Domains\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use UuidTrait;

    protected $table = 'orders';

    protected $fillable = [
        "order_number",
        "vendor_code",
        "sell_price",
        "manager_comment",
        "goods_name",
        "provider_start",
        "amount_in_order",
        "comfy_code",
        "comfy_goods_name",
        "comfy_brand",
        "comfy_category",
        "comfy_price",
        'cost',
        'date_check',
        "comment",
        'amount_in_order_paid',
        'created_by',
        'modified_user_id',
        "created_at",
        "updated_at",
        'manager',
        'order_date',
        'status',
        'defect',
    ];
}