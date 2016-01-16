<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Tag;

use Input,Validator;
class TagController extends Controller{

    public function showGet(){
        $list = Tag::get();
        return view('admin.tag.listTag')->withList($list);
    }

	public function showAdd(){
		return view('admin.tag.addTag');
	}
    public function add(){
        $inputData = Input::get('name');
        $validator = Validator::make(
            array('name'=>$inputData),
            array('name'=>'required|max:255|unique:tags,name')
        );
        if($validator->fails()){
            //Todo:show error page
            //return $validator->messages();
			return view('admin.error')->with(array('errors'=>$validator->messages()));
        }

        try {
            Tag::create(array('name'=>$inputData));
        }catch(\Exception $e){
            //Todo:show error page
            //return 'error';
			return view('admin.error')->with(array('errors'=>"添加数据异常失败！"));
        }

        return view('admin.success')->with(array('messasges'=>"成功添加标签：".$inputData));//Todo::show success page
	}
}