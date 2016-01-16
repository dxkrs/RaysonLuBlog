<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Article;
use Input,Validator;
class ArticleController extends Controller{

    public function showGet(){
        $getData = Article::select('id','title','is_showed')
            ->orderBy('created_at','DESC')
            ->get()
            ->toArray();

        return view('admin.article.listArticle')->with(array('articleList'=>$getData));
    }

    public function showModify(){
        $id = Input::get('id');
        $validator = Validator::make(
            array('id'=>$id),
            array('id'=>'required|integer')
        );
        if($validator->fails()){
            //todo:此处应为错误跳转
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($validator->messages())
            ]);
        }

        $article = Article::find($id);
        if(!$article){
            //todo:此处应为错误跳转
            return response()->json([
                'data'=>false,
                'msg'=>'无此文章！'
            ]);
        }
        //ue.setContent({{isset($article)&&!empty($article['content'])?'htmldecode('.$article['content'].')':''}});
       // $article->content = htmlspecialchars_decode($article->content);
        //die(var_dump($article));
        $getData = Category::select('id','category_name','as_name','parent_id')
            ->get()
            ->toArray();

        $categoryList = tree($getData);

        return view('admin.article.editArticle')->with(array('categoryList'=>$categoryList,'article'=>$article));


    }

    public function showAdd(){
        $getData = Category::select('id','category_name','as_name','parent_id')
            ->get()
            ->toArray();

        $categoryList = tree($getData);
        return view('admin.article.editArticle')->with(array('categoryList'=>$categoryList));
    }
	public function add(){
        $arrFilter = array('category_id','tag_id','cover_pic_id','title','content','desc','is_showed');
        $inputData = Input::only($arrFilter);

        $validator = Validator::make(
            $inputData,
            array('category_id'=>'required|integer|exists:categories,id',
                'title'=>'required|max:255',
                'cover_pic_id'=>'integer',
                'is_showed'=>'required|boolean',
                )
        );
        if($validator->fails()){
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($validator->messages())
            ]);
        }

        try{
            Article::create($inputData);
        }catch(\Exception $e){
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($e->getMessage())
            ]);
        }

        return response()->json([
            'data'=>true,
            'msg'=>'成功添加文章！'
        ]);

	}

    public function delete(){
        $id = Input::get('id');

        $validator = Validator::make(
            array('id'=>$id),
            array('id'=>'required|integer|exists:articles,id')
        );

        if($validator->fails()){
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($validator->messages())
            ]);
        }

        try{
            Article::where('id',$id)->delete();
            //Todo::delete other about this article!!!
        }catch(\Exception $e){
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($e->getMessage())
            ]);
        }

        return response()->json([
            'data'=>true,
            'msg'=>'成功删除文章！'
        ]);


    }
}