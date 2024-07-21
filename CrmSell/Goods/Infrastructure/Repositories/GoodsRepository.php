<?php

namespace CrmSell\Goods\Infrastructure\Repositories;

use CrmSell\Common\Application\Service\DTO\GetListDTO;
use CrmSell\Goods\Domains\Entities\Goods;
use CrmSell\Goods\Infrastructure\Repositories\Interfaces\GoodsRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodsRepository implements GoodsRepositoryInterface
{
    /**
     * @param GetListDTO $getListDTO
     * @return Collection
     * @throws \Exception
     */
    public function getList(GetListDTO $getListDTO): Collection
    {
        try {
            $result = DB::table('goods as g')
                ->select(['g.id', 'g.name', 'g.vendor_code', 'g.deprecated', 'g.created_at', 'g.updated_at'])
                ->limit($getListDTO->getPagination()->getLimit())
                ->offset($getListDTO->getPagination()->getOffset())
                ->orderBy($getListDTO->getSortField(), $getListDTO->getSortDir())
                ->get();
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("GoodsRepository::getListProviders() error.");
        }

        return $result;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getCount(): int
    {
        try {
            $result = DB::table('goods')->select(['id'])->count();
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("GoodsRepository::getCountForListProviders() error.");
        }

        return $result;
    }


    /**
     * @param string $id
     * @param string $fieldName
     * @param string $fieldValue
     * @return bool
     */
    public function checkExistSimilar(string $id, string $fieldName, string $fieldValue): bool
    {
        $goods = Goods::where([
            ['id', '<>', $id],
            [$fieldValue, '=', 'john@example.com']
        ])->first();

        return !empty($goods->id);
    }
}
