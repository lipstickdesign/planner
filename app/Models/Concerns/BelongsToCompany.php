<?php

namespace App\Models\Concerns;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

/**
 * Multi-tenant skopering: alle modeller som bruker denne traiten
 * filtreres automatisk til innlogget selskap (currentCompany), og
 * får company_id satt automatisk ved opprettelse.
 */
trait BelongsToCompany
{
    protected static function bootBelongsToCompany(): void
    {
        static::creating(function ($model) {
            if (empty($model->company_id) && app()->bound('currentCompany')) {
                $model->company_id = app('currentCompany')->id;
            }
        });

        static::addGlobalScope('company', function (Builder $builder) {
            if (app()->bound('currentCompany')) {
                $builder->where(
                    $builder->getModel()->getTable().'.company_id',
                    app('currentCompany')->id
                );
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
