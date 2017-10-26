<?php

namespace Theme\Controllers;

use Theme\Models\Post;
use Themosis\Route\BaseController;

class HomeController extends BaseController
{

    /**
     * @return string
     */
    public function index()
    {
        $data = array();

        /* get slides of upcoming events and pass them to view */
        $eventSlides = $this->createEventsSlides();
        $data = array_add($data, 'eventSlides', $eventSlides);

        /* TODO unused variable now, required in template, possibly remove this */
        $post = new Post();
        $data = array_add($data, 'post', $post);


        /* return view and pass data */
        return view('home', $data);
    }

    /**
     * @return string[][]
     */
    public function createEventsSlides(){

        /* get all activities created by WordPress, only future activities get fetched */
        $events = get_posts([
            'limit' => 3,
            'post_status' => 'future',
            'post_type' => 'activities',
            'orderBy'    => 'date',
            'order' => 'ASC',
            'public' => true
        ]);

        /* creates slider for every activity by creating */
        $eventSlides = array();
        foreach($events as $key => $event){
            $eventSlide = array();

            /* fill slider item with properties extracted from activity */
            $eventSlide = array_add($eventSlide, 'caption', $event->post_title);
            $eventSlide = array_add($eventSlide, 'subCaption', get_the_date('l j F', $event));
            $eventSlide = array_add($eventSlide, 'featuredImage', get_the_post_thumbnail_url($event->ID, 'full'));
            $eventSlide = array_add($eventSlide, 'showButton', true);
            $eventSlide = array_add($eventSlide, 'buttonText', 'Bekijk activiteit');
            $eventSlide = array_add($eventSlide, 'buttonUrl', get_permalink($event));
            $eventSlide = array_add($eventSlide, 'alignment', 'right');

            /* add this sliderItem to list of slider items */
            $eventSlides = array_add($eventSlides, 'eventSlide' . $key, $eventSlide);
        }

        return $eventSlides;
    }
}
