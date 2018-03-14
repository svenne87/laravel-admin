<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermRelation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term_id', 'vocabulary_id',
    ];

	//protected $table = 'term_relations';
    
    /**
     * A TermRelation may belong to single RelationableType.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function relationable() {
        return $this->morphTo();
    }
    
    /**
     * A TermRelation belongs to a Term
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term() {
		return $this->belongsTo(Term::class);
	}

}
