<?php

namespace Sy\Warning\Model;

use Illuminate\Database\Eloquent\Model;

class WarningUserSet extends Model
{
    public $table = 'warning_user_account';

    protected $fillable = ['uid','uname','type','account','show'];
}
