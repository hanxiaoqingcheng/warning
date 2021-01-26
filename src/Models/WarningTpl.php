<?php

namespace Sy\Warning\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WarningTpl extends Model
{
    protected $fillable = ['uid','uname','product','warning_name','warning_tpl','show'];
}
