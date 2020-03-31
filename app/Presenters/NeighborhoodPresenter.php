<?php

namespace App\Presenters;

use App\Transformers\NeighborhoodTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class NeighborhoodPresenter.
 *
 * @package namespace App\Presenters;
 */
class NeighborhoodPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new NeighborhoodTransformer();
    }
}
