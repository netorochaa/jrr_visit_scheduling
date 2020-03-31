<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\PatientType;

/**
 * Class PatientTypeTransformer.
 *
 * @package namespace App\Transformers;
 */
class PatientTypeTransformer extends TransformerAbstract
{
    /**
     * Transform the PatientType entity.
     *
     * @param \App\Entities\PatientType $model
     *
     * @return array
     */
    public function transform(PatientType $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
