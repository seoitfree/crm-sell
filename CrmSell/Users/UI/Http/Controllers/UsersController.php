<?php

namespace CrmSell\Users\UI\Http\Controllers;


use CrmSell\Common\UI\Traits\ResponseTrait;
use CrmSell\Users\Application\User\AddUser\AddUserHandler;
use CrmSell\Users\Application\User\AddUser\Request\AddUser;
use CrmSell\Users\Application\User\GetList\GetListHandler;
use CrmSell\Users\Application\User\GetList\Request\GetList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CrmSell\Users\Domains\Entities\User;

class UsersController
{
    use ResponseTrait;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUser(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (empty($user)) {
            return $this->getErrorsResponse(["Access is denied."]);
        }

        return $this->getSuccessfulResponse([
            "user" => $user->getForInit(),
            "roles" => $user->getRoleNames(),
            "permissions" => []
        ]);
    }

    /**
     * @param Request $request
     * @param AddUserHandler $handler
     * @return JsonResponse
     */
    public function addUser(Request $request, AddUserHandler $handler): JsonResponse
    {
        /* @var User */
        $user = Auth::user();
        if (empty($user) || !$user->hasRole('admin')) {
            return $this->getErrorsResponse(["Access is denied."]);
        }

        $result = $handler->handle(new AddUser($request->toArray()));

        return $this->getResponse($result);
    }

    /**
     * @param Request $request
     * @param GetListHandler $handler
     * @return JsonResponse
     */
    public function getList(Request $request, GetListHandler $handler): JsonResponse
    {
        $user = Auth::user();
        if (empty($user) || !$user->hasRole('admin')) {
            return $this->getErrorsResponse(["Access is denied."]);
        }

        $result = $handler->handle(new GetList($request->toArray()));

        return $this->getResponse($result);
    }
}
