<?php

namespace CrmSell\Users\Infrastructure\Repositories;


use CrmSell\Common\Application\Service\DTO\GetListDTO;
use CrmSell\Users\Infrastructure\Repositories\Interfaces\UsersRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsersRepository implements UsersRepositoryInterface
{
    /**
     * @param GetListDTO $getListDTO
     * @return Collection
     * @throws \Exception
     */
    public function getListUsers(GetListDTO $getListDTO): Collection
    {
        try {
            $result = DB::table('users')
                ->select(['id', 'first_name', 'last_name', 'email', 'status',  'created_at', 'updated_at'])
                ->limit($getListDTO->getPagination()->getLimit())
                ->offset($getListDTO->getPagination()->getOffset())
                ->orderBy($getListDTO->getSortField(), $getListDTO->getSortDir())
                ->get();
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("UsersRepository::getAllCitiesByRegionId() error.");
        }

        return $result;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getCountForListUsers(): int
    {
        try {
            $result = DB::table('users')->select(['id'])->count();
        } catch (QueryException $e) {
            Log::error($e->getMessage() . $e->getTraceAsString());
            throw new \Exception("UsersRepository:: getCountForListUsers() error.");
        }

        return $result;
    }
}
