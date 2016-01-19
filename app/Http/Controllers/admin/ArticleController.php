<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Article;
use App\Model\ArticleTag;
use Input, Validator, DB;

class ArticleController extends Controller
{

    //显示文章列表
    public function showGet()
    {
        $getData = Article::select('id', 'title', 'is_showed')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();

        return view('admin.article.listArticle')->with(array('articleList'=>$getData));
    }

    //显示文章修改页面
    public function showModify()
    {
        $id = Input::get('id');
        $validator = Validator::make(
            array('id'=>$id),
            array('id'=>'required|integer')
        );
        if ($validator->fails()) {
            //todo:此处应为错误跳转
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($validator->messages())
            ]);
        }

        $article = Article::find($id);
        if (!$article) {
            //todo:此处应为错误跳转
            return response()->json([
                'data'=>false,
                'msg'=>'无此文章！'
            ]);
        }

        $getData = Category::select('id', 'category_name', 'as_name', 'parent_id')
            ->get()
            ->toArray();

        $categoryList = tree($getData);

        return view('admin.article.editArticle')->with(array('categoryList'=>$categoryList, 'article'=>$article));


    }

    //显示文章添加页面
    public function showAdd()
    {
        $getData = Category::select('id', 'category_name', 'as_name', 'parent_id')
            ->get()
            ->toArray();

        $categoryList = tree($getData);
        return view('admin.article.editArticle')->with(array('categoryList'=>$categoryList));
    }

    //添加文章操作
    public function add()
    {
        $arrFilter = array('category_id', 'tag_id', 'cover_pic_id', 'title', 'content', 'desc', 'is_showed');
        $inputData = Input::only($arrFilter);

        $validator = Validator::make(
            $inputData,
            array('category_id'=>'required|integer|exists:categories,id',
                'title'=>'required|max:255',
                'cover_pic_id'=>'integer',
                'is_showed'=>'required|boolean',
            )
        );
        if ($validator->fails()) {
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($validator->messages())
            ]);
        }

        try {
            Article::create($inputData);
        } catch (\Exception $e) {
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

    //修改文章操作
    public function modify()
    {
        $arrFilter = array('id', 'category_id', 'tag_id', 'cover_pic_id', 'title', 'content', 'desc', 'is_showed');
        $inputData = Input::only($arrFilter);

        $validator = Validator::make(
            $inputData,
            array(
                'id'=>'required|integer|exists:articles,id',
                'category_id'=>'required|integer|exists:categories,id',
                'title'=>'required|max:255',
                'cover_pic_id'=>'integer',
                'is_showed'=>'required|boolean',
            )
        );

        if ($validator->fails()) {
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($validator->messages())
            ]);
        }

        DB::beginTransaction();
        try {
            $article = Article::find($inputData['id']);
            unset($inputData['id']);
            foreach ($inputData as $k=>$v) {
                $article[$k] = $v;
            }
            $article->save();
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($e->getMessage())
            ]);
        }

        return response()->json([
            'data'=>true,
            'msg'=>'成功修改文章！'
        ]);
    }

    //删除文章操作
    public function delete()
    {
        $id = Input::get('id');

        $validator = Validator::make(
            array('id'=>$id),
            array('id'=>'required|integer|exists:articles,id')
        );

        if ($validator->fails()) {
            return response()->json([
                'data'=>false,
                'msg'=>json_encode($validator->messages())
            ]);
        }

        try {
            Article::where('id', $id)->delete();
            //Todo::delete other about this article!!!
        } catch (\Exception $e) {
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