<?php

namespace App\Exports;

use App\Models\Post;
use Illuminate\Support\Collection as Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PostExport implements FromCollection
{
    protected $last_date;
    protected $today_date;

    public function __construct(String $last_date, String $today_date) 
    {
        $this->last_date = $last_date;
        $this->today_date = $today_date;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        $post_model = new Post();
        $posts = $post_model->readWeeklyPosts($this->last_date, $this->today_date);
        $post_array = [['Title', 'Author', 'Body', 'Tags', 'Created-at', 'Likes']];
        $post_array[] = $post_model->displayPosts($posts);
        return collect($post_array);
    }
}
