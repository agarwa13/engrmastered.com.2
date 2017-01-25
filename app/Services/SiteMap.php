<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 10/16/2015
 * Time: 12:22 PM
 */

namespace App\Services;

use App\Course;
use App\Question;
use Illuminate\Support\Facades\Cache;

class SiteMap
{

    /*
     * Return the content of the Site Map
     */
    public function getSiteMap(){

        if(Cache::has('site-map')){
            return Cache::get('site-map');
        }

        $siteMap = $this->buildSiteMap();
        Cache::add('site-map', $siteMap, 120);
        return $siteMap;
    }

    /*
     * Build the Site Map
     */
    protected function buildSiteMap(){

        $xml = [];

        $xml[] = '<?xml version="1.0" encoding="UTF-8"?'.'>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach(Question::all() as $question){
            $xml[] = '  <url>';
            $xml[] = "    <loc>".url('question/'.$question->id)."</loc>";
            $xml[] = '  </url>';
        }

        foreach(Course::all() as $course){
            $xml[] = '  <url>';
            $xml[] = "    <loc>".url('course/'.$course->id)."</loc>";
            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';

        return join("\n",$xml);
    }

}