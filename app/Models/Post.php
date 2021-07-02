<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    //RELACION DE UNO A MUCHOS INVERSA
    public function user(){
        $this->belongsTo(User::class);
    }

    public function category(){
        $this->belongsTo(Category::class);
    }

    //RELACION DE MUCHOS A MUCHOS
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    //RELACION DE UNO A MUCHOS POLIMORFICA
    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }
}
