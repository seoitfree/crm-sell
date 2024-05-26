<?php

namespace CrmSell\Audit\Application\Service\AddToAudit;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class AddToAudit
{
    /**
     * @param Model $entity
     * @param string $createdBy
     * @param array $auditParams
     * @return void
     */
    public function add(Model $entity, string $createdBy, array $auditParams): void
    {
        $toInsertData = [];
        $dateCreated = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($auditParams as $fieldName => $value) {
            $before = $entity->getAttribute($fieldName);
            $after = $entity->getOriginal($fieldName);

            if ($before !== $after) {
                $type = $value["type"];
                $toInsertData[] = [
                    "id" => Uuid::uuid4(),
                    "parent_id" => $entity->id,
                    "date_created" => $dateCreated,
                    "created_by" => $createdBy,
                    "field_name" => $fieldName,
                    "data_type" => $type,
                    "before_value_string" => $type !== "text" ? $before : '',
                    "after_value_string" => $type !== "text" ? $after : '',
                    "before_value_text" => $type === "text" ? $before : '',
                    "after_value_text" => $type === "text" ? $after : '',
                ];
            }
        }

        foreach (array_chunk($toInsertData, 10) as $chunk) {
            DB::table("{$entity->getTable()}_audit")->insert($chunk);
        }
    }
    //id, parent_id, date_created, created_by, field_name, data_type, before_value_string, before_value_text, after_value_string, after_value_text
}
