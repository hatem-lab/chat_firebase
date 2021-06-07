<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Language extends Model
{
    use Notifiable;
    protected $table='languages';
    protected $fillable = [
        'name', 'abbr', 'direction','active',
    ];
    public function scopeActive($query)
    {
        return $query->where('active',1);
    }
      public function getActive()
    {
        return $this->active==1 ? ' مفعل' : 'غير مفعل';
    }
    public function scopeSelection($query)
    {
        return $query->select('id','active','name','abbr','direction');
    }
}
