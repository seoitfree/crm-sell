<?php

namespace CrmSell\Users\Infrastructure\Repositories\Interfaces;

use CrmSell\Common\Application\Service\DTO\GetListDTO;
use Illuminate\Support\Collection;

interface UsersRepositoryInterface
{
    public function getListUsers(GetListDTO $getListDTO): Collection;

    public function getCountForListUsers(): int;

    public function getUsersRolesList(string $userId): Collection;
}
