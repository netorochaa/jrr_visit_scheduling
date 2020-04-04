<?php

namespace App\Presenters;

use App\Transformers\PersonTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PersonPresenter.
 *
 * @package namespace App\Presenters;
 */
class PersonPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PersonTransformer();
    }
}
