<?php

namespace App\Services;

use App\Models\Site;

class SiteService
{
    public function list() {
        return Site::all();
    }

    public function save($siteData) {
        return Site::create([
            "title" => $siteData['title'],
            "url" => $siteData['url'],
            "selector" => $siteData['selector'],
            "actions" => $siteData['actions']
        ]);
    }

    public function update() {

    }

    public function remove() {

    }


}
