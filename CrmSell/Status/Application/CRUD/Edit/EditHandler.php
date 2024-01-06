<?php

namespace CrmSell\Status\Application\CRUD\Edit;


use CrmSell\Common\Application\Service\Enum\ResponseCodeErrors;
use CrmSell\Common\Application\Service\Handler\AbstractHandler;
use CrmSell\Common\Application\Service\Handler\ResultHandler;
use CrmSell\Common\Application\Service\Request\RequestInterface;
use CrmSell\Status\Application\CRUD\Create\Request\Create;
use CrmSell\Status\Application\CRUD\Edit\Request\Edit;
use CrmSell\Status\Domains\Entities\Status;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditHandler extends AbstractHandler
{
    /**
     * @param RequestInterface $command
     * @return ResultHandler
     */
    protected function process(RequestInterface $command): ResultHandler
    {
        try {
            DB::beginTransaction();

            $this->updateStatus($command);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::warning($e->getMessage() . " " . $e->getTraceAsString());

            $this->notSuccessfulResponse($e);
        }

        return $this->resultHandler;
    }

    /**
     * @param Edit $command
     * @return void
     * @throws \Exception
     */
    private function updateStatus(Edit $command): void
    {
        $status = Status::find($command->getId());
        $this->checkName($command, $status);

        if (empty($status->id)) {
            $this->resultHandler->setStatusCode(404)->setErrors(["Access is denied."])->setStatus(ResponseCodeErrors::BUSINESS_LOGIC_ERROR);
            return;
        }
        if (!$status->update(array_merge($command->toArray(), [
            'modified_user_id' => auth()->id(),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]))) {
            throw new \Exception("Error save, try next time.", 500);
        }

        $this->resultHandler
            ->setStatusCode(200)
            ->setResult([
                "id" => $status->id
                ]);
    }

    /**
     * @param Edit $command
     * @param $
     * @param Status $status
     * @return void
     */
    private function checkName(Edit $command, Status $status): void
    {
        $statusByParams = Status::where("name", $command->getName())->where("type", $command->getType())->first();
        if (!empty($statusByParams->id) && $statusByParams->id !== $status->id) {
            $this->resultHandler->setStatusCode(422)->setErrors([
                [
                    "field" => 'name',
                    "message" => "Alias for type: {$command->getType()} is exist."
                ]
            ])->setStatus(ResponseCodeErrors::VALIDATE_ERROR);
        }
    }
}
