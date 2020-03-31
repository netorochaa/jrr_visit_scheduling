<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Neighborhood;

/**
 * Class NeighborhoodTransformer.
 *
 * @package namespace App\Transformers;
 */
class NeighborhoodTransformer extends TransformerAbstract
{
    /**
     * Transform the Neighborhood entity.
     *
     * @param \App\Entities\Neighborhood $model
     *
     * @return array
     */
    public function transform(Neighborhood $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
