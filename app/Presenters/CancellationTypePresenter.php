<?php

namespace App\Presenters;

use App\Transformers\CancellationTypeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class CancellationTypePresenter.
 *
 * @package namespace App\Presenters;
 */
class CancellationTypePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new CancellationTypeTransformer();
    }
}
