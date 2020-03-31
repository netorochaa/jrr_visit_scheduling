<?php

namespace App\Presenters;

use App\Transformers\PatientTypeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PatientTypePresenter.
 *
 * @package namespace App\Presenters;
 */
class PatientTypePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PatientTypeTransformer();
    }
}
