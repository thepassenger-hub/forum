<?php
namespace App\Filters;
use App\User;

class ThreadFilters extends Filters
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = ['by', 'popular', 'trending', 'contributed_to', 'search'];
    /**
     * Filter the query by a given username.
     *
     * @param  string $username
     * @return Builder
     */
    protected function by($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }
    /**
     * Filter the query according to most popular threads.
     *
     * @return Builder
     */
    protected function popular()
    {   
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Filter the query according to most popular last week.
     *
     * @return Builder
     */
    protected function trending()
    {       
        $this->builder->getQuery()->orders = [];
        return  $this->builder
                ->whereMonth('created_at', \Carbon\Carbon::now()->month)
                ->orderBy('replies_count', 'desc');
    }

    /**
    * Filter the query to show threads where I replied.
    *
    * @return Builder
    */
    protected function contributed_to()
    {
        $user = auth()->user();
        $builder = $this->builder;                
        if ($user) $builder->whereHas('replies', function($query) use ($user){
                                $query->where('user_id', $user->id);
                            });
        return $builder;
    }

    /**
    * Filter the quest to show threads that contain all words entered
    * in the search bar inside the title OR the body OR its replies body.
    *
    * @param string $terms
    * @return Builder
    */

    public function search($terms)
    {
        $terms = explode(' ', $terms);
        $builder = $this->builder;
        $builder->whereHas('replies', function($query) use($terms){
            foreach ($terms as $term) {
                if($term) $query -> where('body', 'like', '%' . $term . '%');
            }
        })
            ->orWhere(function($query) use($terms){
                foreach ($terms as $term) {
                    if($term) $query -> where('body', 'like', "%{$term}%");
                }
            })
            ->orWhere(function($query) use($terms){
                foreach ($terms as $term) {
                    if($term) $query -> where('title', 'like', "%{$term}%");
                }
            });
        return $builder;
    }



    
}