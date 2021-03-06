<?php
/**
 * Created by PhpStorm.
 * User: Loana
 * Date: 23.05.2016
 * Time: 06:40
 */
include_once 'ClassImports.php';
include 'dbAdapter.php';
    
    $response = $_POST['id'];

    //$insertID = -1;
    //$insertID = array();
    switch ($response){
        case 'insertBlog':
            $tblog = new tblog();
            $tblog ->titel = $_POST['title'];
            $tblog ->description= $_POST['description'];
            $tblog ->destination= $_POST['destination'];
            $tblog ->startdate = $_POST['startdate'];
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $insertID = $dbAdapter->insertBlog($tblog);
            $dbAdapter->disconnect();
            break;
        case 'getAllBlogs':
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();            
            $insertID = $dbAdapter->getAllBlogs();
            $dbAdapter->disconnect();
            break;
        case 'getBlogById':
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $blogId = $_POST['blogId'];
            $insertID = $dbAdapter->getBlogByID($blogId);
            $dbAdapter->disconnect();
            break;
        case 'getBlogEntryByID':
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $blogId = $_POST['blogEntryId'];
            $insertID = $dbAdapter->getBlogByID($blogId);
            $dbAdapter->disconnect();
            break;
        case 'deleteBlog':
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $blogId = $_POST["blogid"];
            $insertID = $dbAdapter->deleteEntry($dbAdapter::TABLE_BLOG,$blogId);
            $dbAdapter->disconnect();
            break;
        case 'updateBlog':
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $tblog -> id = $_POST['blogid'];
            $tblog ->titel = $_POST['title'];
            $tblog ->description= $_POST['description'];
            $tblog ->destination= $_POST['destination'];
            $tblog ->startdate = $_POST['startdate'];
            $insertID = $dbAdapter->updateBlog($tblog);
            $dbAdapter->disconnect();
            break;
        case 'insertBlogEntry':
            $tblogentry = new tblogentry();   
            $tblogentry ->blogid= $_POST['blogid'];
            $tblogentry ->titel= $_POST['title'];
            $tblogentry ->picture= $_POST['picture'];
            $tblogentry ->description = $_POST['description'];
            $tblogentry ->createdate = $_POST['createdate'];
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $insertID = $dbAdapter->insertBlogEntry($tblogentry);
            $dbAdapter->disconnect();
            break;
        case 'getBlogEntries':
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $blogId = $_POST['blogId'];
            $insertID = $dbAdapter->getAllBlogEntryByBlogID($blogId);
            $dbAdapter->disconnect();
            break;
        case  'deleteBlogEntries':
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $blogEntryId = $_POST["blogEntryId"];
            $insertID = $dbAdapter->deleteEntry($dbAdapter::TABLE_BLOG_ENTRY,$blogEntryId);
            $dbAdapter->disconnect();
            break;
        case  'updateBlogEntry':
            $tblogentry ->id = $_POST['blogEntryId'];
            $tblogentry ->blogid= $_POST['blogid'];            
            $tblogentry ->titel= $_POST['title'];
            $tblogentry ->picture= $_POST['picture'];
            $tblogentry ->description = $_POST['description'];
            $tblogentry ->createdate = $_POST['createdate'];
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $insertID = $dbAdapter->updateBlogEntry($tblogEntry);
            $dbAdapter->disconnect();
            break;
        case 'getBlogEntryById':
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $blogEntryId = $_POST["blogEntryId"];
            $insertID = $dbAdapter->getBlogEntryByID($blogEntryId);
            $dbAdapter->disconnect();
            break;
        case 'login':
            $username = $_POST["username"];
            $pw = $_POST["pw"];
            $dbAdapter = new dbAdapter();
            $dbAdapter->connect();
            $tbuser = $dbAdapter->getUserByUsername($username);
            $dbAdapter->disconnect();
            if($pw == $tbuser->pw){
                session_start();
                session_unset();
                session_regenerate_id(true);
                $_SESSION['username'] = $username;
                $_SESSION['groups'] = $tbuser->usrgroup;
                $insertID = true;
            }else{
                $insertID = false;
            }
            break;
        case 'auth':
            session_start();
            session_regenerate_id();
            if(!isset($_SESSION['username'])){
                $insertID = false;
            }else{
                $reqgroup = $_POST['reqgroup'];
                if($reqgroup ==-1 || $_SESSION['reqgroup']){
                    $insertID = true;
                }else{
                    $insertID = false;
                }
            }
            break;
        case 'logout':
            session_start();
            unset($_SESSION['username']);
            session_destroy();
            $insertID = true;
            break;
    }
//echo print_r($_POST);
//echo print_r($insertID);
echo json_encode($insertID);

?>