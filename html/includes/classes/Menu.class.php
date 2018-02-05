<?php

class Menu {

    public $slug;
    public $order;
    public $has_parent;
    public $is_not_link;

    public static function getAllMenuItems() {
        global $database;
        $query = "SELECT * FROM cms_menu ORDER BY has_parent DESC,`order`";
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
        $query = "INSERT INTO cms_menu (slug,is_not_link,has_parent, `order`) VALUES (:slug, :is_not_link,:has_parent, :order)";
        $database->insertRow($query, array(
            ':slug' => $pagearray['slug'],
            ':is_not_link' => $pagearray['is_not_link'],
            ':has_parent' => $pagearray['has_parent'],
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
    public static function is_not_link_Menu($slug, $link_type) {
        global $database;
        $query = "UPDATE cms_menu SET `is_not_link`=:is_not_link WHERE slug=:slug";
        $result = $database->updateRow($query, array(
            ':is_not_link'    => $link_type,
            ':slug'           => $slug,
        ));
        return $result;
    }
        public static function updateOrderSubMenu($slug, $order, $parent) {
        global $database;
        $query = "UPDATE cms_menu SET `order`=:order , has_parent=:has_parent WHERE slug=:slug";
        $result = $database->updateRow($query, array(
            ':order'          => $order,
            ':has_parent'     => $parent,
            ':slug'           => $slug,
        ));
        return $result;
    }
    public static function getmenu($lang) {
        global $database;
        $query = "select cms_menu.slug, cms_menu.`order`, cms_pages.title from cms_menu JOIN cms_pages on cms_pages.slug=cms_menu.slug
                WHERE lang=:lang and enabled=:enabled ORDER BY `order`";
        $result_array = $database->getRows($query, array(':lang' => $lang,':enabled' => "1"));
		return !empty($result_array) ? ($result_array) : false;
    }
    public static function getmenuUltimateForm() {
        global $database;
        $query = "SELECT cms_menu.slug,`order`,has_parent,is_not_link, title
FROM cms_menu LEFT OUTER JOIN cms_pages
on cms_pages.slug=cms_menu.slug
ORDER BY has_parent DESC,`order`";
        $result_array = $database->getObj($query, 'Menu');
        return !empty($result_array) ? $result_array : false;
    }





}
