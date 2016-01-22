<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {

	protected $table = "categories";

    protected $guarded = ['id'];

    public function article(){
        return $this->hasMany('App\Model\Article','category_id','id');
    }

}
