<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    //protected $table = 'vocabularies';

    /**
     * A Vocabulary has multiple Terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms() {
        return $this->HasMany(Term::class);
    }
    
    /**
     * A Vocabulary has multiple TermRelations.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function relations() {
        return $this->HasMany(TermRelation::class);
    }

}
