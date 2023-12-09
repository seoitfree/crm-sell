<?php

namespace CrmSell\Users\Application\User\AddUser\Request;


use CrmSell\Common\Application\Service\Request\RootRequest;
use CrmSell\Common\Helpers\Traits\PropertyTrait;

class AddUser extends RootRequest
{
    use PropertyTrait;

    private string $email = '';
    private string $password = '';
    private string $firstName = '';
    private string $lastName = '';
    private array $role = [];

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->mapField($request, ['role']);

        $this->role = !empty($request['role']) ? array_unique($request['role']) : [];
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function toValidation(): array {
        return [
            "email" => $this->email,
            "password" => $this->password,
            "first_name" => $this->firstName,
            "last_name" => $this->lastName,
        ];
    }

    public function getRules(): array
    {
        return [
            "email" => 'required|string|unique:users|email',
            "password" => 'required|string|max:100|min:12',
            "first_name" => 'required|string|max:25|min:2',
            "last_name" => 'required|string|max:25|min:2',
        ];
    }
}
