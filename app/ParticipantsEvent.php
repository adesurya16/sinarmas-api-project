<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParticipantsEvent extends Model
{
    use SoftDeletes;
    protected $table = 'participants_event';
}
