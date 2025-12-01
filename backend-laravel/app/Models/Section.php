<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{
    // 1. Campos liberados para edição
    protected $fillable = ['resume_id', 'type', 'title', 'content', 'order_index'];

    // 2. A CORREÇÃO ESTÁ AQUI 👇
    // Isso diz ao Laravel: "Converta o 'content' para Array quando ler, e para JSON quando salvar".
    protected $casts = [
        'content' => 'array',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }
}