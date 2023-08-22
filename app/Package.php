<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['title', 'language_id', 'currency', 'price', 'description', 'serial_number', 'meta_keywords', 'meta_description'];
}
