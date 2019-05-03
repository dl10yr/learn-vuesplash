<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Photo extends Model
{
    //プライマリキーの型
    protected $keyType = "string";

    //protected $perPage = 2;


    //IDの桁数
    const ID_LENGTH = 12;

    /** JSONに含めるアクセサ */
    protected $appends = [
        'url', 'likes_count', 'liked_by_user',
    ];

    //JSONに含める属性
    protected $visible = [
        'id', 'owner', 'url', 'comments',
        'likes_count', 'liked_by_user',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! array_get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    /**
     * ランダムなID値をid属性に代入する
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     * @return string
     */
    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length -1)];
        }

        return $id;
    }

    /**
     * リレーションシップ usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id', 'id', 'users');
        //モデル,キー,相手のキー, 相手のテーブル名
    }

    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eroquent\Relations\BelongsToMany
     */

    public function likes()
    {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    /**
     * リレーションシップ - commentsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\Hasmany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }

    /**
     * アクセサ url
     * @return string
     */

    public function getUrlAttribute()
    {
        return Storage::cloud()->url($this->attributes['filename']);
    }

    /**
     * アクセサ - likes_count
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    /**
     * アクセサ - liked_by_user
     * @return boolean
     */
    public function getLikedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->likes->contains(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }



}
