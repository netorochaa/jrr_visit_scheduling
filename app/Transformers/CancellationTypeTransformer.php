<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\CancellationType;

/**
 * Class CancellationTypeTransformer.
 *
 * @package namespace App\Transformers;
 */
class CancellationTypeTransformer extends TransformerAbstract
{
    /**
     * Transform the CancellationType entity.
     *
     * @param \App\Entities\CancellationType $model
     *
     * @return array
     */
    public function transform(CancellationType $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
