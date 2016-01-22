<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Tag;
use App\Model\ArticleAttribute;

use Input,Validator;
class TagController extends Controller{

    /* **************** *
     * 控件相关页面显示  *
     * **************** */

    //显示标签列表页面
    public function showGet(){
        $list = Tag::get();
        return view('admin.tag.listTag')->withList($list);
    }

    //显示标签添加页面
	public function showAdd(){
		return view('admin.tag.addTag');
	}




    /* **************** *
     * 控件相关业务操作  *
     * **************** */

    //添加标签
    public function add(){
        $inputData = Input::get('name');
        $validator = Validator::make(
            array('name'=>$inputData),
            array('name'=>'required|max:255|unique:tags,name')
        );
        if($validator->fails()){
			return view('admin.error')->with(array('errors'=>$validator->messages()));
        }

        try {
            Tag::create(array('name'=>$inputData));
        }catch(\Exception $e){
			return view('admin.error')->with(array('errors'=>"添加数据异常失败！"));
        }

        return view('admin.success')->with(array('messages'=>"成功添加标签：".$inputData));
	}

    //修改标签
    public function modify(){
        $inputData = Input::only(array('id','name'));
        $validator = Validator::make(
            $inputData,
            array('id'=>'required|integer','name'=>'required|max:255|unique:tags,name')
        );
        if($validator->fails()){
            return $this->responseJson(false,$validator->messages(),101);
        }

        $tag = Tag::find($inputData['id']);
        if(!$tag){
            return $this->responseJson(false,"不存在该标签！",102);
        }

        $tag->name = $inputData['name'];
        $tag->save();

        return $this->responseJson(true,"成功修改标签!",200);
    }

    //删除标签
    public function delete(){
        $id = Input::get('id');
        $validator = Validator::make(
            array('id'=>$id),
            array('id'=>'required|integer')
        );
        if($validator->fails()){
            return $this->responseJson(false,$validator->messages(),101);
        }

        $tag = Tag::find($id);
        if(!$tag){
            return $this->responseJson(false,"不存在该标签！",102);
        }
        $tag->delete();

        ArticleAttribute::where('attribute_key','tag_id')->where('attribute_value',$id)->delete();

        return $this->responseJson(true,"成功删除标签！",200);
    }




    /* ********** *
     * 类内部方法  *
     * ********** */

}