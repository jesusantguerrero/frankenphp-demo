<?php

namespace App\Services;

use Exception;
use App\Models\Site;
use App\Jobs\ProcessVersions;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class SiteService
{
    const SELECTORS = [
        "github" => [
            "replaceable" => 'https://github.com',
            "selector" => 'a[href*="${replaceable}/releases/tag"]',
            "action" => ['text']
        ],
        "custom" => [
            "replaceable" => 'https://github.com',
            "selector" => 'a[href*="${replaceable}/releases/tag"]',
            "action" => ['text']
        ]
    ];

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

    public function update($id, $data) {
        return Site::find($id)->update($data);
    }

    public function remove() {

    }

    public function runBackground($isSync = false) {
        ProcessVersions::dispatch();
    }

    public function updateCall($site) {
        $results = [];
        try {
            $response = Http::get($site->url);
            $results = $site->actions ? $this->processData($response->body(), $this->getSelectors($site)) : [];

        } catch(Exception $e) {
            throw $e;
            $results = ["Error: {$e->getMessage()}" ];
        };

        if (count($results) > 0 && $site->results !== $results) {
            $this->update($site->id, [
                "results" => $results
            ]);
        }
    }

    public function processData($data, $selectorInstance) {
        $body = new Crawler($data);
        [ "selector" => $selector, "actions" => $actions] = $selectorInstance;
        $selector = $body->filter($selector)?->first()?->text();
        [ "index" => $index ] = $actions[0];
        $index = $index <= 0 ? 1 : $index;
        return [$selector];
        // dd($selector);
        // return $selector ? substr($selector->first()->text(), 0, $index).map((selector, index) => {
        //     $currentAction = $actions[$index] || $actions[0]
        //     ["action" => 'text' , "value" => null } = $currentAction;

        //     return $value ? $(selector)[action](value) : $(selector)[action]()
        // }) : [];
    }


    public function  getSelectors($site) {
        $templatesCollection = collect(self::SELECTORS);
        $page = $templatesCollection->first(fn($template) => strpos($site->url, $template['replaceable']) !== false);

        return [
            "selector" => $site->selector ?? str_replace('${replaceable}', str_replace($page["replaceable"], '', $site->url), $page["selector"]),
            "actions" => $site->action ?? $page["action"]
        ];
    }

}
