<?php

namespace CrmSell\Orders\Application\Orders\OrdersCSV;


use CrmSell\Common\Application\Service\Handler\AbstractHandler;
use CrmSell\Common\Application\Service\Handler\ResultHandler;
use CrmSell\Common\Application\Service\Request\RequestInterface;
use CrmSell\Orders\Application\Orders\OrdersCSV\Request\OrdersCSV;
use CrmSell\Orders\Infrastructure\Repositories\Interfaces\OrdersRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Ramsey\Uuid\Uuid;

class OrdersCSVHandler extends AbstractHandler
{
    private OrdersRepositoryInterface $repository;

    public function __construct(OrdersRepositoryInterface $repository)
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
        $fileName = 'OrdersCSV' . Uuid::uuid4() . '.csv';

        try {
            $fileName = 'report_before_hotline' . time() . '.csv';
            Storage::disk('local')->put("orders/$fileName", '');

            $csv = Writer::createFromPath(storage_path('app/orders/') . $fileName , 'w+');

            $this->createFile($request, $csv);
            $this->resultHandler->setResult([
                'file_name' => $fileName,
                'file_path' => Storage::disk('local')->path("orders/$fileName"),
            ]);
        } catch (\Exception $e) {
            Storage::disk('local')->delete("orders/$fileName");

            Log::warning($e->getMessage() . " " . $e->getTraceAsString());

            $this->notSuccessfulResponse($e);
        }

        return $this->resultHandler;
    }

    /**
     * @param OrdersCSV $command
     * @param Writer $csv
     * @return void
     * @throws \League\Csv\CannotInsertRecord
     * @throws \League\Csv\Exception
     */
    private function createFile(OrdersCSV $command, Writer $csv): void
    {
        $csv->insertOne($this->getHeader());
        foreach ($this->repository->getListOrdersCSV($command) as $order) {
            $csv->insertOne([
                $order['manager'],
                $order['order_date'],
                $order['order_number'],
                $order['vendor_code'],
                $order['goods_name'],
                $order['manager_comment'],
                $order['sell_price'],
                $order['status'],
                $order['amount_in_order_paid'],
                $order['cost'],
                $order['shipments_amount'],
                $order['remainder'],
                $order['provider_start'],
                $order['date_check'],
                $order['comment'],
                $order['defect'],
                $order['comfy_code'],
                $order['comfy_goods_name'],
                $order['comfy_brand'],
                $order['comfy_category'],
                $order['comfy_price'],
                $order['comfy_price_cost'],
            ]);
        }
    }

    /**
     * @return string[]
     */
    private function getHeader(): array
    {
        return [
            'Менеджер',
            'Дата',
            '№ Замовлення',
            'Артикул',
            'Товар',
            'Коментар/уточнення постачальника',
            'Ціна',
            'Статус замовлення',
            'К-ть оплачених',
            'Ціна закупки',
            'К-ть забраних',
            'Залишок',
            'Постачальник',
            'Дата Чеку',
            'Коментар',
            'Списаний',
            'Код номенклатуры',
            'Наименование продукта',
            'Наименование бренда',
            'Наименование категории',
            'Цена/значение',
            'Цена/значение - Ціна закупки'
        ];
    }
}
