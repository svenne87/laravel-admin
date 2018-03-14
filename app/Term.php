<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'vocabulary_id', 'parent', 'weight',
    ];

    /**
     * A Term may belong to multiple TermRelations (RelationableType).
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function termRelation() {
        return $this->morphMany(TermRelation::class, 'relationable');
    }
    
    /**
     * A Term may belong to multiple Vocabularie.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vocabulary() {
        return $this->belongsTo(Vocabulary::class);
    }
     
    /**
     * A Term may have multiple Terms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrens() {
        return $this->hasMany(Term::class, 'parent', 'id')
            ->orderBy('weight', 'ASC');
    }
    
    /**
     * A Term may have single parent Terms.
     *
     * @return \App\Term
     */
    public function parentTerm() {
        return $this->hasOne(Term::class, 'id', 'parent');
    }
}
