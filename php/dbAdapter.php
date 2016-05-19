<?php
/**
 * Created by PhpStorm.
 * User: tbeugste
 * Date: 09.05.2016
 * Time: 12:30
 */

include "ClassImports.php";

class dbAdapter {
    private static $SERVERNAME = "localhost";
    private static $USERNAME= "root";
    private static $PASSWORD  = "webapp06";
    private static $DBNAME = "travelblog";

    const TABLE_USER = "tbusers";
    const TABLE_BLOG = "tblog";
    const TABLE_BLOG_ENTRY = "tblogentry";

    const COLUMN_ID = "id";
    const COLUMN_FIRSTNAME = "firstname";
    const COLUMN_LASTNAME = "lastname";
    const COLUMN_EMAIL = "email";
    const COLUMN_PW = "pw";
    const COLUMN_REG_DATE = "reg_Date";
    const COLUMN_TITEL = "titel";
    const COLUMN_DESCRIPTION ="description";
    const COLUMN_DESTINATION ="destination";
    const COLUMN_STARTDATE = "startdate";
    const COLUMN_BLOGID = "blogid";
    const COLUMN_PICTURE = "picture";
    const COLUMN_CREATEDATE = "createdate";

    /** @var  mysqli $conn*/
    private $conn;

    function connect()
    {
         $this->conn = new mysqli(self::$USERNAME, self::$USERNAMEUSERNAME, self::$PASSWORD, self::$DBNAME);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    function disconnect(){
        $this->conn->close();
    }

    function getBlogByID($id){
        $blog = new tblog();
        $sql = "Select * FROM ".self::TABLE_BLOG." WHERE ".self::COLUMN_ID." = ".$id;
        $result = $this->conn->query($sql);
        if($result->num_rows>0) {
            $blog = $this->execQuerySingleResult($result, tblog::class);
        }
        return $blog;
    }

    function getBlogEntryByID($id){
        $blogEntry = new tblogentry();
        $sql = "Select * FROM ".self::TABLE_BLOG_ENTRY." WHERE ".self::COLUMN_ID." = ".$id;
        $result = $this->conn->query($sql);
        if($result->num_rows>0) {
            $blogEntry = $this->execQuerySingleResult($result, tblogentry::class);
        }
        return $blogEntry;
    }

    function getAllBlogEntryByBlogID($id){
        $allBlogEntries = [];
        $blogEntry = new tblogentry();
        $sql = "Select * FROM ".self::TABLE_BLOG_ENTRY." WHERE ".self::COLUMN_BLOGID." = ".$id." ORDER BY ".self::COLUMN_ID;
        $result = $this->conn->query($sql);
        while($blogEntry = $this->execQuerySingleResult($result, tblogentry::class)) {
            $allBlogEntries[] = $blogEntry;
        }
        return $blogEntry;
    }

    function getUserByID($id){
        $user = new tbuser();
        $sql = "Select * FROM ".self::TABLE_USER." WHERE ".self::COLUMN_ID." = ".$id;
        $result = $this->conn->query($sql);
        if($result->num_rows>0) {
            $user = $this->execQuerySingleResult($result, tbuser::class);
        }
        return $user;
    }

    /** @var tblog $tblog
     * @return integer $insertID
     */
    function insertBlog($tblog){
        $insertID = -1;
        $query = "INSERT INTO '".self::TABLE_BLOG."'('".self::COLUMN_TITEL."', '".self::COLUMN_DESCRIPTION."', '".
            self::COLUMN_DESTINATION."', '".self::COLUMN_STARTDATE."')
            VALUES ('".$tblog->titel."', '".$tblog->description."', '".$tblog->destination."', '".$tblog->startdate."')";
        $result = $this->conn->real_query($query);
        if($result){
            $insertID = $this->conn->insert_id;
        }
        return $insertID;
    }

    /** @var tbuser $user
     * @return integer */
    function insertUser($user){
        $insertID = -1;
        $query = "INSERT INTO '".self::TABLE_USER."'('".self::COLUMN_FIRSTNAME."', '".self::COLUMN_LASTNAME."', '".
            self::COLUMN_EMAIL."', '".self::COLUMN_PW."', '".self::COLUMN_REG_DATE."')
            VALUES ('".$user->firstname."', '".$user->lastname."', '".$user->email."', '".$user->pw."', '".$user->reg_Date."')";
        $result = $this->conn->real_query($query);
        if($result){
            $insertID = $this->conn->insert_id;
        }
        return $insertID;
    }

    /** @var tblogentry $tblogentry
     * @return integer $insertID
     */
    function insertBlogEntry($tblogentry){
        $insertID = -1;
        $query = "INSERT INTO '".self::TABLE_BLOG_ENTRY."'('".self::COLUMN_BLOGID."', '".self::COLUMN_TITEL."', '".
            self::COLUMN_PICTURE."', '".self::COLUMN_DESCRIPTION."', '".self::COLUMN_CREATEDATE."')
            VALUES ('".$tblogentry->blogid."', '".$tblogentry->titel."', '".$tblogentry->picture."', '".$tblogentry->description."', '".$tblogentry->createdate."')";
        $result = $this->conn->real_query($query);
        if($result){
            $insertID = $this->conn->insert_id;
        }
        return $insertID;
    }

    private function execQuerySingleResult($resultSet, $class){
        return $resultSet->fetch_object($class);
    }
}