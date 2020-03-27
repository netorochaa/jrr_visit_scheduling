<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Collector;

/**
 * Class CollectorTransformer.
 *
 * @package namespace App\Transformers;
 */
class CollectorTransformer extends TransformerAbstract
{
    /**
     * Transform the Collector entity.
     *
     * @param \App\Entities\Collector $model
     *
     * @return array
     */
    public function transform(Collector $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
