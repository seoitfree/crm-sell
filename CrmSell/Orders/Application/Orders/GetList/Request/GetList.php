<?php

namespace CrmSell\Orders\Application\Orders\GetList\Request;


class GetList extends \CrmSell\Common\Application\Service\Request\GetList
{
    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        parent::__construct($request);
    }

    /**
     * @return array
     */
    public function getFilter(): array
    {
        return [];
    }
}
