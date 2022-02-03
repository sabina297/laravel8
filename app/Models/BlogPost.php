<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use SoftDeletes;

    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['title', 'content', 'user_id'];
    
    public function comments()
    {
        return $this->hasMany('App\Models\Comment')->latest();
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag')->withTimestamps()->as('tagged');
    }
    
    //SCOPE
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        //comments_counts   
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    public function scopeLatestWithRelations(Builder $query)
    {
        $query->latest()
        ->withCount('comments')
        ->with('tags')
        ->with('user');
    }
//END SCOPE

    public static function boot()
    {
        static::addGlobalScope(new DeletedAdminScope);
        
        parent::boot(); // stergerea elementelor folosind soft deletes

        static::deleting(function (BlogPost $blogPost) {
            $blogPost->comments()->delete();
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        });

        ///la upodate-ul unui blog post sa se actualizeze datele in cache
        static::updating(function (BlogPost $blogPost) {
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        });

        static::restoring(function (BlogPost $blogPost) {
            $blogPost->comments()->restore();
        });

        //GLOBAL QUERY SCOPES - se aplica tuturor query-urilor aplicate modelului
        //static::addGlobalScope(new LatestScope);

        //stergerea elementelor ce sunt chei externe in alte tabele prin setarea unui eveniment static in model
        //static::deleting( function(BlogPost $blogPost){

          // $blogPost->comments()->delete();

        //});
    }
}
