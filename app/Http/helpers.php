<?php

/**
 * Create a conversation slug.
 *
 * @param  string $title
 * @return string
 */
function makeSlugFromTitle($title)
{
    $slug = str_slug($title);

    $count = \App\Thread::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

    return $count ? "{$slug}-{$count}" : $slug;
};