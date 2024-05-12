<?php

namespace CrmSell\Orders\Application\Orders\Update;


use CrmSell\Common\Application\Service\Enum\ResponseCodeErrors;
use CrmSell\Common\Application\Service\Handler\AbstractHandler;
use CrmSell\Common\Application\Service\Handler\ResultHandler;
use CrmSell\Common\Application\Service\Request\RequestInterface;
use CrmSell\Orders\Application\Orders\Create\Request\Create;
use CrmSell\Orders\Application\Orders\Update\Request\Update;
use CrmSell\Orders\Domains\Entities\Order;
use CrmSell\Orders\Infrastructure\Repositories\Interfaces\ShipmentsRepositoryInterface;
use CrmSell\Providers\Domains\Entities\Provider;
use CrmSell\Status\Domains\Entities\Defect;
use CrmSell\Status\Domains\Entities\Status;
use CrmSell\Status\Domains\Enum\OrderStatusEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateHandler extends AbstractHandler
{
    private ShipmentsRepositoryInterface $repository;

    /**
     * @param ShipmentsRepositoryInterface $repository
     */
    public function __construct(ShipmentsRepositoryInterface $repository)
    {
        parent::__construct();

        $this->repository = $repository;
    }

    /**
     * @param RequestInterface $request
     * @return ResultHandler
     */
    protected function process(RequestInterface $request): ResultHandler
    {
        try {
            DB::beginTransaction();

            $this->update($request);

            DB::commit();
        } catch (\DomainException $e) {
            DB::rollBack();

            $this->resultHandler->setStatusCode(400)
                ->setErrors([$e->getMessage()])
                ->setStatus(ResponseCodeErrors::BUSINESS_LOGIC_ERROR);

            return $this->resultHandler;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage() . " " . $e->getTraceAsString());

            $this->notSuccessfulResponse($e);
        }

        return $this->resultHandler;
    }

    /**
     * @param Update $request
     * @return void
     * @throws \Exception
     */
    private function update(Update $request): void
    {
        $order = Order::find($request->getEntityId());

        if (empty($order->id)) {
            $this->resultHandler->setStatusCode(404)
                ->setErrors(["Order does not exist {$request->getEntityId()}."])
                ->setStatus(ResponseCodeErrors::BUSINESS_LOGIC_ERROR);
            return;
        }

        $this->check($order, $request);
        $order->fill(array_merge($request->forUpdate(), [
            'modified_user_id' => auth()->id(),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]));

        if ($order->save()) {
            throw new \Exception("Error save, try next time.", 500);
        }

        $this->resultHandler
            ->setStatusCode(200)
            ->setResult([
                "id" => $order->id
            ]);
    }

    /**
     * @param Order $order
     * @param Update $request
     * @return void
     */
    private function check(Order $order, Update $request): void
    {
        $forUpdate = $request->forUpdate();
        $totalShipmentForByOrder = $this->repository->getTotalShipmentForByOrder($request->getEntityId());

        if ($request->getFieldName() === "amount_in_order_paid") {
            $order->changeAmountInOrderPaid($forUpdate["amount_in_order_paid"], $totalShipmentForByOrder);
        }
        if ($request->getFieldName() === "amount_in_order") {
            $order->changeAmountInOrderPaid($forUpdate["amount_in_order"], $totalShipmentForByOrder);
        }
        if (!Status::firstOrNew(['alias' => $forUpdate['status']])->exist) {
            throw new \DomainException("Status does not exist: {$forUpdate['status']}");
        }
        if (!Defect::firstOrNew(['alias' => $forUpdate['defect']])->exist) {
            throw new \DomainException("Defect does not exist: {$forUpdate['defect']}");
        }
        if (!Provider::firstOrNew(['id' => $forUpdate['provider']])->exist) {
            throw new \DomainException("Provider does not exist: {$forUpdate['provider']}");
        }
    }
}
