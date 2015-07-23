<?php

namespace App\Http\Controllers\Root;

use App\Models\PostTag;
use App\Models\Tags;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use View;
use Redirect;
use Notifications;

class TagsController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Tags',
            'tags' => Tags::i()->allWithPostsCount(),
        ];
        $this->title->prepend($data['title']);
        View::share('menu_item_active', 'tags');

        return view('root.tags.index', $data);
    }

    public function clearOrphaned()
    {
        $tags = Tags::i()->allWithPostsCount();
        foreach ($tags as $tag) {
            if($tag->num == 0) {
                Tags::destroy($tag->id);
            }
        }
        Notifications::add('Empty tags removed', 'success');
        return Redirect::back();
    }

    public function remove($tag_id)
    {
        Tags::destroy($tag_id);
        PostTag::where(['tag_id' => $tag_id])->delete();

        Notifications::add('Tag removed', 'success');
        return Redirect::back();
    }
}
