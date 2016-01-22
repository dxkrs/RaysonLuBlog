<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model {

    use SoftDeletes;

    protected $table = "articles";

	protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function category(){
        return $this->belongsTo('App\Model\Category','category_id','id');
    }
}
