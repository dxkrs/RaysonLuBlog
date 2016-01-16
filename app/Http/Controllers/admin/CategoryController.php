<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;

use Input,Validator;

class CategoryController extends Controller{
	
	public function showGet(){
		$getData = Category::select('id','category_name','as_name','parent_id')
            ->get()
            ->toArray();
		
        $list = tree($getData);
		return view('admin.category.listCategory')->with(array('list'=>$list));
    }

	public function showAdd(){
		$getData = Category::select('id','category_name','as_name','parent_id')
            ->get()
            ->toArray();
		
        $list = tree($getData);
		return view('admin.category.addCategory')->with(array('list'=>$list));
	}
	
    public function add(){
        $arrFilter = array('category_name','as_name','parent_id',
		'seo_title','seo_key','seo_desc');

		$inputData = Input::only($arrFilter);
		$validator = Validator::make(
            $inputData,
            array(
                'category_name'=>'required|max:255|unique:categories,category_name',
                'as_name'=>'required|max:255|unique:categories,as_name',
                'parent_id'=>'required|integer',
                'seo_title'=>'max:255','seo_key'=>'max:255','seo_desc'=>'max:255'
            )
        );
		if($validator->fails()){
            //Todo:show error page
            //return $validator->messages();
			return view('admin.error')->with(array('errors'=>$validator->messages()));
        }

        //若不是顶级分类，查看是否存在父分类
        if($inputData['parent_id'] != 0){
            $res = Category::find($inputData['parent_id']);
            if(!$res){
              
			  //Todo:show error page
              // return 'error';
			  return view('admin.error')->with(array('errors'=>"不存在该父分类！"));
            }
        }

        try {
            Category::create($inputData);
        }catch(\Exception $e){
            //Todo:show error page
            //return 'error';
			return view('admin.error')->with(array('errors'=>"添加数据异常失败！"));
        }

        return view('admin.success')->with(array('messages'=>"成功添加分类：".$inputData['category_name']));//Todo::show success page
	}
}