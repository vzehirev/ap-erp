<?php

namespace App\Traits;

use App\Models\SoldMaterial;

trait UpdateMaterialsAvailableQuantities
{
    static function bootUpdateMaterialsAvailableQuantities()
    {
        static::created(function ($model) {
            if ($model->from_material) {
                $model->from_material->decreaseAvailableQuantity($model->quantity);
            }

            if ($model->material) {
                if ($model::class == SoldMaterial::class) {
                    $model->material->decreaseAvailableQuantity($model->quantity);
                } else {
                    $model->material->increaseAvailableQuantity($model->quantity);
                }
            } elseif ($model->to_material) {
                $model->to_material->increaseAvailableQuantity($model->quantity);
            }
        });
    }
}
