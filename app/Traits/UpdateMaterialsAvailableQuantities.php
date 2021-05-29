<?php

namespace App\Traits;

trait UpdateMaterialsAvailableQuantities
{
    static function bootUpdateMaterialsAvailableQuantities()
    {
        static::created(function ($model) {
            if ($model->from_material) {
                $model->from_material->decreaseAvailableQuantity($model->quantity);
            }

            if ($model->material) {
                $model->material->increaseAvailableQuantity($model->quantity);
            } elseif ($model->to_material) {
                $model->to_material->increaseAvailableQuantity($model->quantity);
            }
        });
    }
}
