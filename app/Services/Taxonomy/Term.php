<?php

namespace App\Services\Taxonomy;

use Devfactory\Taxonomy\Models\Term as TermParent;

class Term extends TermParent
{
    /**
     * Return the description attribute
     */
    public function getDescriptionAttribute()
    {
        return 'Ollle';
    }
}
