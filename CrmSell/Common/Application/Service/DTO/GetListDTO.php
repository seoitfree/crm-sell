<?php

namespace CrmSell\Common\Application\Service\DTO;

use CrmSell\Common\Components\Pagination\PaginationInterface;

class GetListDTO
{
    private string $sortField = '';
    private string $sortDir = '';

    private PaginationInterface $pagination;


    public function __construct(string $sortField)
    {
        $this->sortField = $sortField;
    }

    static function create(string $sortField): self {
        return new GetListDTO($sortField);
    }

    public function setSortDir(string $sortDir): self
    {
        $this->sortDir = $sortDir;

        return $this;
    }

    public function setPagination(PaginationInterface $pagination): self
    {
        $this->pagination = $pagination;

        return $this;
    }

    public function getPagination(): PaginationInterface { return $this->pagination; }
    public function getSortDir(): string { return $this->sortDir; }
    public function getSortField(): string { return $this->sortField; }
}
