<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'quiz_id'];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
