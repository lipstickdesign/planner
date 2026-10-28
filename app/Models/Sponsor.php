<?php

namespace App\Models;

use App\Models\Concerns\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'name', 'logo_path', 'sort_order'];

    public function card(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->logo_path,
        ];
    }
}
