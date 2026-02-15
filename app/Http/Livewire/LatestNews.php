<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\News;

class LatestNews extends Component
{
    public $limit = 3;
    public $showFeatured = true;

    public function getNewsProperty()
    {
        $query = News::published()
            ->with('author:id,name')
            ->orderBy('published_at', 'desc');

        if ($this->showFeatured) {
            $query->featured();
        }

        return $query->limit($this->limit)->get();
    }

    public function render()
    {
        return view('livewire.latest-news', [
            'news' => $this->news,
        ]);
    }
}
