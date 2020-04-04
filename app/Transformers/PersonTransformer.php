<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Person;

/**
 * Class PersonTransformer.
 *
 * @package namespace App\Transformers;
 */
class PersonTransformer extends TransformerAbstract
{
    /**
     * Transform the Person entity.
     *
     * @param \App\Entities\Person $model
     *
     * @return array
     */
    public function transform(Person $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
