<?php

class Page {

    public $id;
    public $slug;
    public $title;
    public $content;
    public $meta_keywords;
    public $meta_description;
    public $lang;
    public $enabled;

    public static function getAllPages() {
        global $database;
        $query = "SELECT * FROM cms_pages";
        $result_array = $database->getObj($query, 'Page');
        return !empty($result_array) ? $result_array : false;
    }
    public static function getAllPagesByLang() {
        global $database;
        $query = "SELECT * FROM cms_pages ORDER BY lang,slug";
        $result_array = $database->getObj($query, 'Page');
        return !empty($result_array) ? $result_array : false;
    }

    public static function getByEnabledStatus($enabled) {
        global $database;
        $query = "SELECT * FROM cms_pages WHERE enabled=:enabled";
        $result_array = $database->getObj($query, 'Page', array(':enabled' => $enabled));
		return !empty($result_array) ? $result_array : false;
    }

    public static function getSinglePageById($page_id) {
        global $database;
        $query = "SELECT * FROM cms_pages WHERE id=:page_id LIMIT 1";
        $result_array = $database->getObj($query, 'Page', array(':page_id' => $page_id));
		return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function getSinglePageTltleBySlug($slug) {
        global $database;
        $query = "SELECT title FROM cms_pages WHERE slug=:slug LIMIT 1";
        $result_array = $database->getObj($query, 'Page', array(':slug' => $slug));
		return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function checkForDublicatePage($slug,$lang) {
        global $database;
        $query = "SELECT * FROM cms_pages WHERE slug=:slug  AND lang=:lang LIMIT 1";
        $result_array = $database->getObj($query, 'Page', array(':slug' => $slug,':lang' => $lang));
		return !empty($result_array) ? array_shift($result_array) : false;
    }

    public static function fetchAllAvailablePageSlags() {
        global $database;
        $query = "SELECT distinct slug FROM cms_pages";
        $result_array = $database->getRows($query);
		return !empty($result_array) ? ($result_array) : false;
    }

    public static function fetchPageSlagCount($slug) {
        global $database;
        $query = "SELECT count(*) AS cou FROM cms_pages where slug=:slug";
        $result_array = $database->getRows($query,  array(':slug' => $slug));
		return !empty($result_array) ? ($result_array) : false;
    }

    public static function insertPage($pagearray) {
        global $database;
        $query = "INSERT INTO cms_pages (slug, title, content, meta_description, meta_keywords, lang, enabled) VALUES (:slug, :title, :content, :meta_description, :meta_keywords, :lang, :enabled)";
        $database->insertRow($query, array(
            ':slug' => $pagearray['slug'],
            ':title' => $pagearray['title'],
            ':content' => $pagearray['content'],
            ':meta_description' => $pagearray['meta_description'],
            ':meta_keywords' => $pagearray['meta_keywords'],
            ':lang' => $pagearray['lang'],
            ':enabled'=> $pagearray['enabled']
        ));
        $result = $database->getInsertedId();
        return $result;
    }

    public static function updatePage($pagearray, $id) {
        global $database;
        $query = "UPDATE cms_pages SET slug=:slug, title=:title, content=:content, meta_description=:meta_description, meta_keywords=:meta_keywords, lang=:lang, enabled=:enabled WHERE id=:id";
        $result = $database->updateRow($query, array(
            ':slug' => $pagearray['slug'],
            ':title' => $pagearray['title'],
            ':content' => $pagearray['content'],
            ':meta_description' => $pagearray['meta_description'],
            ':meta_keywords' => $pagearray['meta_keywords'],
            ':lang' => $pagearray['lang'],
            ':enabled'=> $pagearray['enabled'],
            ':id'=>$id
        ));
        return $result;
    }

     public static function deletePage( $id) {
        global $database;
        $query = "DELETE FROM cms_pages WHERE id=:id";
        $result = $database->deleteRow($query, array(':id'=>$id));
        return $result;
    }

    public static function getAllPageLang() {
        global $database;
        $query = "SELECT DISTINCT lang FROM cms_pages";
        $result_array = $database->getRows($query);
		return !empty($result_array) ? $result_array : false;
    }

    public static function getAllPagesOrdered($lang) {
        global $database;
        $query = "SELECT  cms_menu.`order`, cms_pages.title,cms_pages.slug, cms_pages.content FROM cms_menu JOIN cms_pages ON cms_pages.slug=cms_menu.slug WHERE lang=:lang and enabled=:enabled ORDER BY `order`";
        $result_array = $database->getRows($query, array(':lang' => $lang,':enabled' => "1"));
        return !empty($result_array) ? ($result_array) : false;
    }
}
