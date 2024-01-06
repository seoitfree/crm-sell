<?php

namespace CrmSell\Status\Application\CRUD\Create;


use CrmSell\Common\Application\Service\Enum\ResponseCodeErrors;
use CrmSell\Common\Application\Service\Handler\AbstractHandler;
use CrmSell\Common\Application\Service\Handler\ResultHandler;
use CrmSell\Common\Application\Service\Request\RequestInterface;
use CrmSell\Status\Application\CRUD\Create\Request\Create;
use CrmSell\Status\Domains\Entities\Status;
use CrmSell\Status\Domains\Enum\StatusEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CreateHandler extends AbstractHandler
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
                    "id" => $this->addStatus($command)
                ]);

            DB::commit();
        } catch (\DomainException $e) {
            DB::rollBack();
            return $this->resultHandler;
        }  catch (\Exception $e) {
            DB::rollBack();

            Log::warning($e->getMessage() . " " . $e->getTraceAsString());

            $this->notSuccessfulResponse($e);
        }

        return $this->resultHandler;
    }

    /**
     * @param Create $command
     * @return string
     * @throws \Exception
     */
    private function addStatus(Create $command): string
    {
        $this->checkAlias($command);
        $this->checkName($command);

        $userId = auth()->id();
        $date = Carbon::now()->format('Y-m-d H:i:s');

        $status = Status::create(array_merge($command->toArray(), [
            'created_by' => $userId,
            'modified_user_id' => $userId,
            'created_at' => $date,
            'updated_at' => $date,
        ]));

        if (!$status->save()) {
            throw new \Exception("Error save, try next time.", 500);
        }

        return $status->id;
    }

    /**
     * @param Create $command
     * @return void
     */
    private function checkAlias(Create $command): void
    {
        $status = Status::where("alias", $command->getAlias())->where("type", $command->getType())->first();
        if (!empty($status->id)) {
            $this->resultHandler->setStatusCode(422)->setErrors([
                [
                    "field" => 'alias',
                    "message" => "Alias for type: {$command->getType()} is exist."
                ]
            ])->setStatus(ResponseCodeErrors::VALIDATE_ERROR);
        }
    }

    /**
     * @param Create $command
     * @return void
     */
    private function checkName(Create $command): void
    {
        $status = Status::where("name", $command->getName())->where("type", $command->getType())->first();
        if (!empty($status->id)) {
            $this->resultHandler->setStatusCode(422)->setErrors([
                [
                    "field" => 'name',
                    "message" => "Alias for type: {$command->getType()} is exist."
                ]
            ])->setStatus(ResponseCodeErrors::VALIDATE_ERROR);
        }
    }
}
