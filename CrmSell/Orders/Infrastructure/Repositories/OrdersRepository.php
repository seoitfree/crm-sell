<?php

namespace CrmSell\Orders\Infrastructure\Repositories;


use CrmSell\Common\Application\Service\DTO\GetListDTO;
use CrmSell\Orders\Infrastructure\Repositories\Interfaces\OrdersRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrdersRepository implements OrdersRepositoryInterface
{
    /**
     * @param array $filter
     * @return int
     * @throws \Exception
     */
    public function getListCount(array $filter): int
    {
        //$results = DB::select('select * from users where id = :id', ['id' => 1]);
        try {
            $results = DB::select("SELECT COUNT(o.id) as count FROM orders as o");
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("OrdersRepository::getListCount() error.");
        }

        return !empty($results) ? $results[0]->count : 0;
    }

    /*
     SELECT o.id,
       o.amount_in_order,
       o.amount_in_order_paid,
       o.sell_price,
       o.cost,
       o.date_check,
       o.order_date,
       o.order_number,
       o.vendor_code,
       o.goods_name,
       o.manager_comment,
       o.comment,
       o.comfy_code,
       o.comfy_goods_name,
       o.comfy_brand,
       o.comfy_category,
       CONCAT(COALESCE(u.first_name,''), ' ', COALESCE(u.last_name,'')) as manager,
       s.name as status,
       d.name as defect,
       p.name as provider,
       shipments.shipments_amount
FROM orders as o
    LEFT JOIN users u
       ON o.manager = u.id
    LEFT JOIN status s
       ON s.alias = o.status
    LEFT JOIN defects d
       ON d.alias = o.defect
    LEFT JOIN providers p
       ON p.id = o.provider_start
    LEFT JOIN (
        SELECT order_id,
               shipments_amount,
               t.rn
        FROM (SELECT order_id,
                     IF(CONVERT(@prev USING utf8) <> CONVERT(order_id USING utf8), @rn:=0, @rn),
                     IF(CONVERT(@prev USING utf8) <> CONVERT(order_id USING utf8), @shipments_amount:=0, @shipments_amount:=@shipments_amount + amount) as shipments_amount,
                     @prev:=order_id,
                     @rn:=@rn+1 AS rn
              FROM shipments, (SELECT @rn:=0) rn, (SELECT @prev:='' ) prev, (SELECT @shipments_amount:=0) shipments_amount
              ORDER BY order_id ASC
             ) AS t
        WHERE t.rn = 1
    ) as shipments
        ON shipments.order_id = o.id
    */

    /**
     * @param GetListDTO $dto
     * @return array
     * @throws \Exception
     */
    public function getList(GetListDTO $dto): array
    {
        try {
            $sql = "
            SELECT o.id,
                   o.amount_in_order as amount_in_orderamount_in_order,
                   o.amount_in_order_paid as amount_in_order_paid,
                   o.sell_price as sell_price,
                   o.cost as cost,
                   o.date_check as date_check,
                   o.order_date as order_date,
                   o.order_number as order_number,
                   o.vendor_code as vendor_code,
                   o.goods_name as goods_name,
                   o.manager_comment as manager_comment,
                   o.comment as comment,
                   o.comfy_code as comfy_code,
                   o.comfy_goods_name as comfy_goods_name,
                   o.comfy_brand as comfy_brand,
                   o.comfy_category as comfy_category,
                   o.comfy_price as comfy_price,
                   o.created_at,
                   CONCAT(COALESCE(u.first_name,''), ' ', COALESCE(u.last_name,'')) as manager,
                   s.name as status,
                   d.name as defect,
                   p.name as provider,
                   shipments.shipments_amount as shipments_amount,
                   o.amount_in_order_paid - shipments.shipments_amount as remainder
            FROM orders as o
                LEFT JOIN users u
                   ON o.manager = u.id
                LEFT JOIN status s
                   ON s.alias = o.status
                LEFT JOIN defects d
                   ON d.alias = o.defect
                LEFT JOIN providers p
                   ON p.id = o.provider_start
                LEFT JOIN (
                    SELECT order_id,
                           shipments_amount,
                           t.rn
                    FROM (SELECT order_id,
                                 IF(CONVERT(@prev USING utf8) <> CONVERT(order_id USING utf8), @rn:=0, @rn),
                                 IF(CONVERT(@prev USING utf8) <> CONVERT(order_id USING utf8), @shipments_amount:=0, @shipments_amount:=@shipments_amount + amount) as shipments_amount,
                                 @prev:=order_id,
                                 @rn:=@rn+1 AS rn
                          FROM shipments, (SELECT @rn:=0) rn, (SELECT @prev:='' ) prev, (SELECT @shipments_amount:=0) shipments_amount
                          ORDER BY order_id ASC
                         ) AS t
                    WHERE t.rn = 1
                ) as shipments
                    ON shipments.order_id = o.id
                ORDER BY {$dto->getSortField()} {$dto->getSortDir()}
                LIMIT {$dto->getPagination()->getLimit()} OFFSET {$dto->getPagination()->getOffset()}
            ";

            $results = DB::select($sql);
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("OrdersRepository::getList() error.");
        }

        return !empty($results) ? $results : [];
    }
}
