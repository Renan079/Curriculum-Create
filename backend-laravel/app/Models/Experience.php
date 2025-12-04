<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = ['resume_id', 'company', 'role', 'start_date', 'end_date', 'description'];

    // Relacionamento inverso (opcional, mas bom ter)
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}