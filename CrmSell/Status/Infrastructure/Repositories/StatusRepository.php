<?php

namespace CrmSell\Status\Infrastructure\Repositories;


use CrmSell\Common\Application\Service\DTO\GetListDTO;
use CrmSell\Status\Infrastructure\Repositories\Interfaces\StatusRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatusRepository implements StatusRepositoryInterface
{
    /**
     * @param GetListDTO $getListDTO
     * @return Collection
     * @throws \Exception
     */
    public function getListStatus(GetListDTO $getListDTO): Collection
    {
        try {
            $result = DB::table('status as s')
                ->select(['s.id', 's.name', 's.alias', 's.type', 'created_at', 'updated_at'])
                ->where("s.type", $getListDTO->getFilterValue('type'))
                ->limit($getListDTO->getPagination()->getLimit())
                ->offset($getListDTO->getPagination()->getOffset())
                ->orderBy($getListDTO->getSortField(), $getListDTO->getSortDir())
                ->get();
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("StatusRepository::getListStatus() error.");
        }

        return $result;
    }

    /**
     * @param string $type
     * @return int
     * @throws \Exception
     */
    public function getCountForListStatus(string $type): int
    {
        try {
            $result = DB::table('status')->select(['id'])->where("type", $type)->count();
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("StatusRepository::getCountForListStatus() error.");
        }

        return $result;
    }
}
