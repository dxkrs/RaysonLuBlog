<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Article;
use App\Model\ArticleAttribute;
use App\Model\Tag;
use Input, Validator, DB;

class ArticleController extends Controller
{

    /* **************** *
     * 控件相关页面显示  *
     * **************** */

    //显示文章列表
    public function showGet()
    {
        $getData = Article::select('id', 'title', 'is_showed')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->toArray();

        return view('admin.article.listArticle')->with(array('articleList' => $getData));
    }

    //显示文章修改页面
    public function showModify()
    {
        $id = Input::get('id');
        $validator = Validator::make(
            array('id' => $id),
            array('id' => 'required|integer')
        );
        if ($validator->fails()) {
            //todo:此处应为错误跳转
            return response()->json([
                'data' => false,
                'msg' => json_encode($validator->messages())
            ]);
        }

        $article = Article::find($id);
        if (!$article) {
            //todo:此处应为错误跳转
            return response()->json([
                'data' => false,
                'msg' => '无此文章！'
            ]);
        }

        $getData = Category::select('id', 'category_name', 'as_name', 'parent_id')
            ->get()
            ->toArray();

        $categoryList = tree($getData);

        $arrTagIds = ArticleAttribute::where('article_id', $id)
            ->where('attribute_key', 'tag_id')
            ->lists('attribute_value')->toArray();
        if (count($arrTagIds)) {
            $arrTags = Tag::whereIn('id', $arrTagIds)->lists('name');
            foreach ($arrTags as $value) {
                $article['tags'] .= $value . " ";
            }

            $article['tags'] = substr($article['tags'], 0, strlen($article['tags']) - 1);
            //dd($article['tags']);
        }
        return view('admin.article.editArticle')->with(array('categoryList' => $categoryList, 'article' => $article));


    }

    //显示文章添加页面
    public function showAdd()
    {
        $getData = Category::select('id', 'category_name', 'as_name', 'parent_id')
            ->get()
            ->toArray();

        $categoryList = tree($getData);
        return view('admin.article.editArticle')->with(array('categoryList' => $categoryList));
    }





    /* **************** *
     * 控件相关业务操作  *
     * **************** */

    //添加文章操作
    public function add()
    {
        $arrFilter = array('category_id', 'cover_pic_id', 'title', 'content', 'desc', 'is_showed');
        $inputData = Input::only($arrFilter);
        $tags = trim(Input::get('tags'));
        $validator = Validator::make(
            $inputData,
            array('category_id' => 'required|integer|exists:categories,id',
                'title' => 'required|max:255',
                'cover_pic_id' => 'integer',
                'is_showed' => 'required|boolean',
            )
        );
        if ($validator->fails()) {
            return response()->json([
                'data' => false,
                'msg' => json_encode($validator->messages())
            ]);
        }

        DB::beginTransaction();
        try {
            $article = Article::create($inputData);
            if ($tags != null) {
                $this->saveTags($article->id, $tags);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => false,
                'msg' => json_encode($e->getMessage())
            ]);
        }

        return response()->json([
            'data' => true,
            'msg' => '成功添加文章！'
        ]);

    }

    //修改文章操作
    public function modify()
    {
        $arrFilter = array('id', 'category_id', 'cover_pic_id', 'title', 'content', 'desc', 'is_showed');
        $inputData = Input::only($arrFilter);
        $tags = trim(Input::get('tags'));

        $validator = Validator::make(
            $inputData,
            array(
                'id' => 'required|integer|exists:articles,id',
                'category_id' => 'required|integer|exists:categories,id',
                'title' => 'required|max:255',
                'cover_pic_id' => 'integer',
                'is_showed' => 'required|boolean',
            )
        );

        if ($validator->fails()) {
            return response()->json([
                'data' => false,
                'msg' => json_encode($validator->messages())
            ]);
        }

        DB::beginTransaction();
        try {
            $article = Article::find($inputData['id']);
            unset($inputData['id']);
            foreach ($inputData as $k => $v) {
                $article[$k] = $v;
            }
            $article->save();
            if ($tags != null) {
                $this->saveTags($article->id, $tags);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'data' => false,
                'msg' => json_encode($e->getMessage())
            ]);
        }

        return response()->json([
            'data' => true,
            'msg' => '成功修改文章！'
        ]);
    }

    //删除文章操作
    public function delete(){
        $id = Input::get('id');

        $validator = Validator::make(
            array('id' => $id),
            array('id' => 'required|integer|exists:articles,id')
        );

        if ($validator->fails()) {
            return response()->json([
                'data' => false,
                'msg' => json_encode($validator->messages())
            ]);
        }

        try {
            Article::where('id', $id)->delete();
            //Todo::delete other about this article!!!
        } catch (\Exception $e) {
            return response()->json([
                'data' => false,
                'msg' => json_encode($e->getMessage())
            ]);
        }

        return response()->json([
            'data' => true,
            'msg' => '成功删除文章！'
        ]);


    }





    /* ********** *
     * 类内部方法  *
     * ********** */

    protected function saveTags($id, $tags)
    {
        //获取标签数组，去重复
        $arrTags = array_unique(explode(' ', $tags));
        if (count($arrTags)) {
            /*储存新的标签名*/
            //取出所有标签名
            $allTags = Tag::lists('name')->toArray();
            //比较获得新标签名
            $newTags = array_diff($arrTags, $allTags);
            //若有新标签则添加
            if (count($newTags)) {
                $arrInsert = array();
                foreach ($newTags as $tag) {
                    $arrInsert[] = array(
                        'name' => $tag,
                        'created_at'=>date('Y-m-d h:i:s',time()),
                        'updated_at'=>date('Y-m-d h:i:s',time())
                    );
                }
                Tag::insert($arrInsert);
            }

            /*建立文章与标签的关系*/
            //取出标签的id
            $tagIds = Tag::whereIn('name', $arrTags)->lists('id')->toArray();
            //取出已存在的关系
            $allAttrTagIds = ArticleAttribute::where('article_id', $id)
                ->where('attribute_key', 'tag_id')
                ->lists('attribute_value')
                ->toArray();
            //新增关系
            $newAttrTagIds = array_diff($tagIds, $allAttrTagIds);
            if (count($newAttrTagIds)) {
                $arrInsert = array();
                foreach ($newAttrTagIds as $newAttrTagId) {
                    $arrInsert[] = array(
                        'article_id' => $id,
                        'attribute_key' => 'tag_id',
                        'attribute_value' => $newAttrTagId,
                        'created_at'=>date('Y-m-d H:i:s',time()),
                        'updated_at'=>date('Y-m-d H:i:s',time())
                    );
                }
                ArticleAttribute::insert($arrInsert);
                Tag::whereIn('id', $newAttrTagIds)->increment('use_number');
            }

            //删除关系
            $delAttrTagIds = array_diff($allAttrTagIds, $tagIds);
            if (count($delAttrTagIds)) {
                ArticleAttribute::where('article_id', $id)
                    ->where('attribute_key', 'tag_id')
                    ->whereIn('attribute_value', $delAttrTagIds)
                    ->delete();
                Tag::whereIn('id', $delAttrTagIds)->decrement('use_number');
            }
        }
    }

}