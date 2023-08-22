<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageOrder extends Model
{
    protected $fillable = ['name', 'email', 'fields', 'nda', 'package_title', 'package_currency', 'package_price', 'status', 'package_description'];
}
