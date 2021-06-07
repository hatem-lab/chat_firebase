<?php

namespace App\Models;
use App\Observers\MainCategoryObserver;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;
class MainCategory extends Model
{
    protected $table='main_categories';
    protected $fillable = [
        'translation_lang', 'translation_of', 'slug','photo','active','name',
    ];
    public function scopeActive($query)
    {
        return $query->where('active',1);
    }
    public function scopeSelection($query)
    {
        return $query->select('id','active','name','translation_lang','photo', 'slug','translation_of');
    }
    public function getActive()
    {
        return $this->active==1 ? ' مفعل' : 'غير مفعل';
    }
    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";

    }
    public function categories()
    {
        return $this->hasMany(self::class, 'translation_of');
    }
    public function vendors(){

        return $this -> hasMany('App\Models\Vendor','category_id','id');
    }
    protected static function boot()
    {
        parent::boot();
        MainCategory::observe(MainCategoryObserver::class);
    }

    public function scopeDefaultCategory($query){
        return  $query -> where('translation_of',0);
    }
    public  function subCategories(){
        return $this -> hasMany(SubCategory::class,'category_id','id');
    }
}
