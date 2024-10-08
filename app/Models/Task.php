<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'status', 'due_date', 'assigned_to', 'image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    

    // In Task Model
    protected $casts = [
        'created_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    

}
