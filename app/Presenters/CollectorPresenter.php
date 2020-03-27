<?php

namespace App\Presenters;

use App\Transformers\CollectorTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CollectorPresenter.
 *
 * @package namespace App\Presenters;
 */
class CollectorPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CollectorTransformer();
    }
}
