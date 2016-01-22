<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;

use Input,Validator;

class CategoryController extends Controller{

    /* **************** *
     * 控件相关页面显示  *
     * **************** */

    //显示分类列表页面
	public function showGet(){
		$getData = Category::select('id','category_name','as_name','parent_id')
            ->get()
            ->toArray();
		
        $list = tree($getData);
		return view('admin.category.listCategory')->with(array('list'=>$list));
    }

    //显示分类添加页面
	public function showAdd(){
		$getData = Category::select('id','category_name','as_name','parent_id')
            ->get()
            ->toArray();
		
        $list = tree($getData);
		return view('admin.category.addCategory')->with(array('list'=>$list));
	}





    /* **************** *
     * 控件相关业务操作  *
     * **************** */

    //添加分类
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

    //修改分类
    public function modify(){
        $arrFilter = array('id','category_name','as_name','parent_id',
            'seo_title','seo_key','seo_desc');

        $inputData = Input::only($arrFilter);
        $validator = Validator::make(
            $inputData,
            array(
                'id'=>'required|integer',
                'category_name'=>'required|max:255|unique:categories,category_name',
                'as_name'=>'required|max:255|unique:categories,as_name',
                'parent_id'=>'required|integer',
                'seo_title'=>'max:255','seo_key'=>'max:255','seo_desc'=>'max:255'
            )
        );
        if($validator->fails()){
            return $this->responseJson(false,$validator->messages(),101);
        }

        $category = Category::find($inputData['id']);
        if(!$category){
            return $this->responseJson(false,"找不到改分类！",102);
        }
        unset($inputData['id']);

        $res = $category->update($inputData);
        if(!$res){
            return $this->responseJson(false,"修改分类失败！",199);
        }
        return $this->responseJson(true,"修改分类成功！",200);
    }

    //删除分类
    public function delete(){
        $id = Input::get('id');
        $validator = Validator::make(
            array('id'=>$id),
            array('id'=>'required|integer')
        );
        if($validator->fails()){
            return $this->responseJson(false,$validator->messages(),101);
        }
        $category = Category::find($id);
        if(!$category){
            return $this->responseJson(false,"不存在该分类！",102);
        }

        $childdrenCategory = Category::where('parent_id',$id)->first();
        if(!count($childdrenCategory)){
            return $this->responseJson(false,"该分类存在子分类！",103);
        }

        $article = $category->article->first();
        if(count($article)){
            return $this->responseJson(false,"该分类下存在文章！",104);
        }

        $res = $category->delete();
        if(!$res){
            return $this->responseJson(false,"删除分类失败！",199);
        }
        return $this->responseJson(true,'删除分类成功！',200);
    }





    /* ********** *
     * 类内部方法  *
     * ********** */

}