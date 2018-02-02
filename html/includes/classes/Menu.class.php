<?php

class Menu {

    public $slug;
    public $order;

    public static function getAllMenuItems() {
        global $database;
        $query = "SELECT * FROM cms_menu ORDER BY `order`";
        $result_array = $database->getObj($query, 'Menu');
        return !empty($result_array) ? $result_array : false;
    }

    public static function getSingleMenuBySlug($slug) {
        global $database;
        $query = "SELECT * FROM cms_menu WHERE slug=:slug LIMIT 1";
        $result_array = $database->getObj($query, 'Menu', array(':slug' => $slug));
		return !empty($result_array) ? ($result_array) : false;
    }
    public static function getSingleMenuByOrder($order) {
        global $database;
        $query = "SELECT * FROM cms_menu WHERE `order`=:order LIMIT 1";
        $result_array = $database->getObj($query, 'Menu', array(':order' => $order));
		return !empty($result_array) ? ($result_array) : false;
    }
    public static function insertMenuItems($pagearray) {
        global $database;
        $query = "INSERT INTO cms_menu (slug, `order`) VALUES (:slug, :order)";
        $database->insertRow($query, array(
            ':slug' => $pagearray['slug'],
            ':order' => $pagearray['order']
        ));
        $result = $database->getInsertedId();
        return $result;
    }
     public static function deleteMenuItems($slug) {
        global $database;
        $query = "DELETE FROM cms_menu WHERE slug=:slug";
        $result = $database->deleteRow($query, array(':slug'=>$slug));
        return $result;
    }
    public static function updateOrderMenu($slug, $order) {
        global $database;
        $query = "UPDATE cms_menu SET `order`=:order WHERE slug=:slug";
        $result = $database->updateRow($query, array(
            ':order'          => $order,
            ':slug'           => $slug,
        ));
        return $result;
    }
    public static function getmenu($lang) {
        global $database;
        $query = "select cms_menu.slug, cms_menu.`order`, cms_pages.title from cms_menu JOIN cms_pages on cms_pages.slug=cms_menu.slug WHERE lang=:lang and enabled=:enabled ORDER BY `order`";
        $result_array = $database->getRows($query, array(':lang' => $lang,':enabled' => "1"));
		return !empty($result_array) ? ($result_array) : false;
    }





}
