<?php
namespace YusufUsta;

use Exception;
use GuzzleHttp\Client;

class Gist {
    public $logged = false;

    public function __construct($UserName = NULL, $Token = NULL, array $Settings = []) {
        $OriginalSettings = [
            'base_uri' => 'https://api.github.com/',
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json'
            ]
        ];

        if (empty($UserName) || empty($Token)) {
            $this->logged = false;
        } else {
            $this->logged = true;
            $OriginalSettings = array_merge($OriginalSettings, ['auth' => [
                $UserName, 
                $Token
            ]]);
        }

        $Settings = array_merge($OriginalSettings, $Settings);

        $this->Client = new Client($Settings);
    }

    public function sendRequest($url, $method, $settings) {
        return $this->Client->request($method, $url, $settings);
    }

    public function getGists($since = NULL, $perPage = NULL, $page = NULL) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return json_decode($this->sendRequest('/gists', 'GET', ['query' => ['since' => $since, 'per_page' => $perPage, 'page' => $page]])->getBody(), true);
    }

    public function createGist($files, $desc = '', $public = false) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return json_decode($this->sendRequest('/gists', 'POST', ['json' => ['description' => $desc, 'public' => $public, 'files' => $files]])->getBody(), true);
    }

    public function createFile($fileName, $content) {
        return [$fileName => ['content' => $content]];
    }

    public function getPublicGists($since = NULL, $perPage = NULL, $page = NULL) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return json_decode($this->sendRequest('/gists/public', 'GET', ['query' => ['since' => $since, 'per_page' => $perPage, 'page' => $page]])->getBody(), true);
    }

    public function getStarredGists($since = NULL, $perPage = NULL, $page = NULL) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return json_decode($this->sendRequest('/gists/starred', 'GET', ['query' => ['since' => $since, 'per_page' => $perPage, 'page' => $page]])->getBody(), true);
    }

    public function getGist($gist_id) {
        return json_decode($this->sendRequest('/gists/' . $gist_id, 'GET', [])->getBody(), true);
    }

    public function updateGist($gist_id, $files, $desc) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return json_decode($this->sendRequest('/gists/' . $gist_id, 'PATCH', ['json' => ['description' => $desc, 'files' => $files]])->getBody(), true);
    }

    public function deleteGist($gist_id) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return $this->sendRequest('/gists/' . $gist_id, 'DELETE', []);
    }

    public function getGistCommits($gist_id, $perPage =NULL, $page=NULL) {
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/commits', 'GET', ['query' => ['per_page' => $perPage, 'page' => $page]])->getBody(), true);
    }

    public function getGistForks($gist_id, $perPage =NULL, $page=NULL) {
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/forks', 'GET', ['query' => ['per_page' => $perPage, 'page' => $page]])->getBody(), true);
    }

    public function forkGist($gist_id) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/forks', 'POST', [])->getBody(), true);
    }

    public function starGist($gist_id) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/star', 'PUT', [])->getBody(), true);
    }

    public function unstarGist($gist_id) {
        if (!$this->logged) throw new Exception("You must login to use this function.", 0);
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/star', 'DELETE', [])->getBody(), true);
    }

    public function getGistRevision($gist_id, $sha) {
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/' . $sha, 'GET', [])->getBody(), true);
    }

    public function getUserGists($user, $since = NULL, $perPage = NULL, $page = NULL) {
        return json_decode($this->sendRequest('/user/' . $user . '/gists', 'GET', ['query' => ['since' => $since, 'per_page' => $perPage, 'page' => $page]])->getBody(), true);
    }

    public function getGistComments($gist_id, $perPage = NULL, $page = NULL) {
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/comments', 'GET', ['query' => ['per_page' => $perPage, 'page' => $page]])->getBody(), true);
    }

    public function sendGistComment($gist_id, $Comment) {
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/comments', 'POST', ['json' => ['body' => $Comment]])->getBody(), true);
    }

    public function getGistComment($gist_id, $comment_id) {
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/comments/' . $comment_id, 'GET', [])->getBody(), true);
    }

    public function updateGistComment($gist_id, $comment_id, $comment) {
        return json_decode($this->sendRequest('/gists/' . $gist_id . '/comments/' . $comment_id, 'PATCH', ['json' => ['body' => $comment]])->getBody(), true);
    }

    public function deleteGistComment($gist_id, $comment_id, $comment) {
        return $this->sendRequest('/gists/' . $gist_id . '/comments/' . $comment_id, 'DELETE', []);
    }
}