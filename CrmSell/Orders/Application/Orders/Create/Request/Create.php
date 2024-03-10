<?php

namespace CrmSell\Orders\Application\Orders\Create\Request;


use CrmSell\Common\Application\Service\Request\RootRequest;
use CrmSell\Common\Helpers\Traits\PropertyTrait;

class Create extends RootRequest
{
    use PropertyTrait;

    private string $numberOrder = '';
    private string $vendorCode = '';

    private float $sellPrice =  0.0;
    private string $managerComment = '';

    private string $goodsName = '';
    private string $provider = '';

    private int $amountInOrder = 0;
    private string $comfyCode = '';

    private string $comfyGoodsName = '';
    private string $comfyBrand = '';

    private string $comfyCategory = '';
    private float $comfyPrice = 0.0;

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->mapField($request, ['roles']);
    }

    public function toValidation(): array
    {
        return [
            "numberOrder" => $this->numberOrder,
            "vendorCode" => $this->vendorCode,
            "sellPrice" => $this->sellPrice,
            "managerComment" => $this->managerComment,
            "goodsName" => $this->goodsName,
            "provider" => $this->provider,
            "amountInOrder" => $this->amountInOrder,
            "comfyCode" => $this->comfyCode,
            "comfyGoodsName" => $this->comfyGoodsName,
            "comfyBrand" => $this->comfyBrand,
            "comfyCategory" => $this->comfyCategory,
            "comfyPrice" => $this->comfyPrice,
        ];
    }

    public function getRules(): array
    {
        return [
            "numberOrder" => 'required|string|max:50',
            "vendorCode" => 'required|string|max:50',
            "sellPrice" => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|gt:0',
            "managerComment" => 'required|string|max:1000',
            "goodsName" => 'required|string|max:150',
            "provider" => 'required|string|exists:CrmSell\Providers\Domains\Entities\Provider,id',
            "amountInOrder" => 'required|numeric|gt:0',

            "comfyCode" => 'required|string|max:50',
            "comfyGoodsName" => 'required|string|max:150',
            "comfyBrand" => 'required|string|max:50',
            "comfyCategory" => 'required|string|max:150',
            "comfyPrice" => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|gt:0',
        ];
    }

    /**
     * @return array
     */
    public function toMap(): array
    {
        return [
            "number_order" => $this->numberOrder,
            "vendor_code" => $this->vendorCode,
            "sell_price" => $this->sellPrice,
            "manager_comment" => $this->managerComment,
            "goods_name" => $this->goodsName,
            "provider" => $this->provider,
            "amount_in_order" => $this->amountInOrder,
            "comfy_code" => $this->comfyCode,
            "comfy_goods_name" => $this->comfyGoodsName,
            "comfy_brand" => $this->comfyBrand,
            "comfy_category" => $this->comfyCategory,
            "comfy_price" => $this->comfyPrice,
        ];
    }

    public function messages(): array
    {
        return [
            'sellPrice.regex' => 'Цена должна быть дробным с двумя знаками после запятой.',
            'sellPrice.gt' => 'Цена должна быть больше 0.',
            'amountInOrder.gt' => 'Количество заказов должно быть больше 0.',
            'comfyPrice.regex' => 'Цена comfy должна быть дробным с двумя знаками после запятой.',
            'comfyPrice.gt' => 'Цена comfy должна быть больше 0.',
        ];
    }
}
