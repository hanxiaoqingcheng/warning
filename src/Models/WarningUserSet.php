<?php

namespace Sy\Warning\Models;

use Illuminate\Database\Eloquent\Model;

class WarningUserSet extends Model
{
    public $table = 'warning_user_account';

    protected $fillable = ['uid','uname','type','account','show'];
}
