<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'description', 'title'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    /**
     * A Post have single Author (User).
     *
     * @return \App\User
     */
    public function author() {
        return $this->belongsTo(User::class);
    }

    /**
     * Assign the given User to the Post.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function setAuthor(User $user) {
        $this->user_id = $user->id;
        $this->save();
    }

    /**
     * @return string
     */
    public function getLinkAttribute()
    {
        return route('page', ['pageSlug' => $this->slug]);
    }
}
