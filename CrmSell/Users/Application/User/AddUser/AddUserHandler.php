<?php

namespace CrmSell\Users\Application\User\AddUser;


use CrmSell\Common\Application\Service\Handler\AbstractHandler;
use CrmSell\Common\Application\Service\Handler\ResultHandler;
use CrmSell\Common\Application\Service\Request\RequestInterface;
use CrmSell\Users\Application\User\AddUser\Request\AddUser;
use CrmSell\Users\Domains\Entities\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use CrmSell\Users\Domains\Entities\User;

class AddUserHandler extends AbstractHandler
{
    /**
     * @param RequestInterface $command
     * @return ResultHandler
     */
    protected function process(RequestInterface $command): ResultHandler
    {
        try {
            DB::beginTransaction();

            $this->resultHandler
                ->setStatusCode(201)
                ->setResult([
                    "id" => $this->addUser($command)
                ]);

            DB::commit();
        } catch (\DomainException $e) {
            DB::rollBack();

            $this->notSuccessfulResponse($e);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::warning($e->getMessage() . " " . $e->getTraceAsString());

            $this->notSuccessfulResponse($e);
        }

        return $this->resultHandler;
    }

    /**
     * @param AddUser $command
     * @return string
     * @throws \Exception
     */
    private function addUser(AddUser $command): string
    {
        $user = User::create([
            "email" => $command->getEmail(),
            "password" => bcrypt($command->getPassword()),
            "first_name" => $command->getFirstName(),
            "last_name" => $command->getLastName(),
        ]);
        if (!$user->save()) {
            throw new \Exception("Error save, try next time.", 500);
        }

        $this->checkRoles($command);
        $user->assignRole($command->getRules());

        return $user->id;
    }

    /**
     * @param AddUser $command
     * @return void
     */
    private function checkRoles(AddUser $command): void
    {
        $roles = Role::whereIn('name', $command->getRules())->get();

        $rolesDiff = array_diff($command->getRules(), $roles->map(fn (Role $item) =>  $item->name));
        if (count($rolesDiff) > 0) {
            $rolesString = implode(",", $rolesDiff);
            throw new \DomainException("Roles does $rolesString not exist.", 500);
        }
    }
}