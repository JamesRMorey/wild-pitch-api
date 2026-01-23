<?php

namespace App\Http\Traits;

use Carbon\Carbon;

trait IsPublic
{

    public function isPublic(): bool
    {
        return $this->status == 'PUBLIC';
//            && $this->published_at
//            && $this->published_at <= Carbon::now();
    }
}
