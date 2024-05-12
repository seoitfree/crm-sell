<?php

namespace CrmSell\Orders\Application\Orders\GetList\Request;


class GetList extends \CrmSell\Common\Application\Service\Request\GetList
{
    private array $filterParams = [];
    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        parent::__construct($request);

        $this->filterParams = !empty($request['filterParams']) ? $request['filterParams'] : [];
    }

    public function getFilter(): array { return $this->filterParams; }
}
