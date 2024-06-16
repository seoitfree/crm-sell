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
        $params = $this->getFilter($filter);
        $where = implode("AND", array_filter($params["condition"], fn($item) => $item !== ''));
        $where = $where === '' ? $where : " AND $where ";

        try {
            $sql = "SELECT COUNT(t.id) as count
                FROM ({$this->getQuerySQL()}) as t
                $where";

            $results = DB::select($sql, $params["bindings"]);
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("OrdersRepository::getListCount() error.");
        }

        return !empty($results) ? $results[0]->count : 0;
    }

    /**
     * @return string
     */
    public function getQuerySQL(): string
    {
        return "SELECT o.id,
                   o.amount_in_order as amount_in_order,
                   o.amount_in_order_paid as amount_in_order_paid,
                   o.sell_price as sell_price,
                   o.cost as cost,
                   IFNULL(o.date_check, '') as date_check,
                   o.created_at as order_date,
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
                   s.alias as status_alias,
                   d.name as defect,
                   o.defect as defect_alias,
                   p.id as provider_start_id,
                   p.name as provider_start,
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
                    SELECT s.order_id, SUM(s.amount) as shipments_amount
                    FROM shipments as s
                    GROUP BY order_id
                ) as shipments
                    ON shipments.order_id = o.id";
    }

    /**
     * @param GetListDTO $dto
     * @return array
     * @throws \Exception
     */
    public function getList(GetListDTO $dto): array
    {
        $params = $this->getFilter($dto->getFilter());
        $where = implode("AND", array_filter($params["condition"], fn($item) => $item !== ''));
        $where = $where === '' ? $where : " AND $where ";

        try {
            $sql = "
                SELECT t.*
                FROM ({$this->getQuerySQL()}) as t
                $where
                ORDER BY {$dto->getSortField()} {$dto->getSortDir()}
                LIMIT {$dto->getPagination()->getLimit()} OFFSET {$dto->getPagination()->getOffset()}
            ";

            $results = DB::select($sql, $params["bindings"]);
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("OrdersRepository::getList() error.");
        }

        return !empty($results) ? $results : [];
    }

    /**
     * @param array $params
     * @return array|array[]
     */
    private function getFilter(array $params): array
    {
        $filter = [
            "condition" => [],
            "bindings" => [],
        ];

        if (!empty($params["order_date_from"])) {
            $filter["condition"][] = " t.created_at >= :order_date_from";
            $filter["bindings"]["order_date_from"] = $params["order_date_from"];
        }
        if (!empty($params["order_date_to"])) {
            $filter["condition"][] = " t.created_at <= :order_date_to";
            $filter["bindings"]["order_date_to"] = $params["order_date_to"];
        }
        if (!empty($params["date_check_from"])) {
            $filter["condition"][] = " t.date_check >= :date_check_from";
            $filter["bindings"]["date_check_from"] = $params["date_check_from"];
        }
        if (!empty($params["date_check_to"])) {
            $filter["condition"][] = " t.created_at <= :date_check_to";
            $filter["bindings"]["date_check_to"] = $params["date_check_to"];
        }
        if (!empty($params["vendor_code"])) {
            $filter["condition"][] = " t.vendor_code = :vendor_code";
            $filter["bindings"]["vendor_code"] = $params["vendor_code"];
        }
        if (!empty($params["goods_name"])) {
            $filter["condition"][] = " t.goods_name LIKE :goods_name";
            $filter["bindings"]["goods_name"] = "{$params["goods_name"]}";
        }
        if (!empty($params["status"])) {
            $filter["condition"][] = " t.status IN (:status)";
            $filter["bindings"]["status"] = "{$params["status"]}";
        }
        if (!empty($params["remainder"])) {
            $filter["condition"][] = " t.status IN (:status)";
            $filter["bindings"]["remainder"] = "t.remainder > 0";
        }
        if (!empty($params["provider_start"])) {
            $filter["condition"][] = " t.provider_start = :provider_start";
            $filter["bindings"]["provider_start"] = $params["provider_start"];
        }
        if (!empty($params["defect"])) {
            $filter["condition"][] = " t.defect = :defect";
            $filter["bindings"]["provider_start"] = $params["defect"];
        }
        if (!empty($params["comment"])) {
            $filter["condition"][] = " t.comment LIKE :comment";
            $filter["bindings"]["goods_name"] = "{$params["comment"]}";
        }

        return $filter;
    }
}
