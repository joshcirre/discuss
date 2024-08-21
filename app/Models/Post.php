<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class Post extends Model
{
    use HasFactory;
    use UsesTenantConnection;

    protected $guarded = ['id'];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withoutTenancy();
    }

    public function loadLandlordUser()
    {
        $this->setRelation('user', User::find($this->user_id));

        return $this;
    }
}
