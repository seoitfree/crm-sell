<?php

namespace CrmSell\Status\Domains\Entities;


use CrmSell\Common\Domains\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use UuidTrait;

    protected $table = 'status';

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'alias',
        'created_by',
        'modified_user_id',
        "created_at",
        "updated_at",
    ];

    public function getDetail(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "type" => 'status',
            "alias" => $this->alias,
        ];
    }
}
