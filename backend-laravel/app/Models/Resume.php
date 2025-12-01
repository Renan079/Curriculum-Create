<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <--- Importante
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    protected $fillable = ['user_id', 'title', 'template_id', 'primary_color', 'font_family'];

    // --- ESTA FUNÇÃO ESTAVA FALTANDO OU COM NOME ERRADO ---
    public function sections(): HasMany
    {
        // Garante que as seções venham na ordem certa
        return $this->hasMany(Section::class)->orderBy('order_index');
    }
    // -------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}