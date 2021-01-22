<?php

namespace Sy\Warning\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WarningLog extends Model
{
    protected $fillable = [
        'uid',
        'name',
        'product',
        'warning_name',
        'occur_time',
        'type',
        'message',
        'account',
        'status',
        'times',
        'show'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = 'warning_logs_'.date('Ym');

        DB:: statement("create table if not exists "."$this->table like `warning_logs`");
    }
}
