<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['first_name', 'last_name', 'will_attend', 'is_pregnant', 'allergies', 'dietary_requirements', 'notes', 'rsvp_type', 'table_number', 'has_gift', 'receives_favor'])]
class Rsvp extends Model
{
    /**
     * Get the members associated with the RSVP.
     */
    public function members(): HasMany
    {
        return $this->hasMany(RsvpMember::class);
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
            'allergies' => 'encrypted',
            'dietary_requirements' => 'encrypted',
            'notes' => 'encrypted',
            'table_number' => 'integer',
            'has_gift' => 'boolean',
            'receives_favor' => 'boolean',
        ];
    }
}
