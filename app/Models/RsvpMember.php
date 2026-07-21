<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'rsvp_id', 'member_type', 'first_name', 'last_name', 'will_attend', 
    'is_pregnant', 'age', 'needs_highchair', 'needs_baby_menu', 
    'allergies', 'dietary_requirements', 'notes', 'table_number',
    'has_gift', 'receives_favor'
])]
class RsvpMember extends Model
{
    /**
     * Get the RSVP that owns the member.
     */
    public function rsvp(): BelongsTo
    {
        return $this->belongsTo(Rsvp::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'will_attend' => 'boolean',
            'is_pregnant' => 'boolean',
            'needs_highchair' => 'boolean',
            'needs_baby_menu' => 'boolean',
            'age' => 'integer',
            'allergies' => 'encrypted',
            'dietary_requirements' => 'encrypted',
            'notes' => 'encrypted',
            'table_number' => 'integer',
            'has_gift' => 'boolean',
            'receives_favor' => 'boolean',
        ];
    }
}
