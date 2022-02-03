<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['content', 'user_id'];

    public function blogPost()
    {
        return $this->belongsTo('App\Models\BlogPost');
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public static function boot()
    {
        parent::boot();

        static::creating(function (Comment $comment) {
            Cache::tags(['blog-post'])->forget("blog-post-{$comment->blog_post_id}");
            Cache::tags(['blog-post'])->forget('mostCommented');
        });

        //GLOBAL QUERY SCOPES
        //static::addGlobalScope(new LatestScope);

    }
}
