<?php 
include_once("functions.php");
include_once("find_token.php");
include_once("error_handler.php");

if(!isset($_GET['type'])){
    echo ajax_echo(
        "Ошибка!",
        "Вы не указали GET параметр type!",
        "ERROR",
        null
    );
    exit;
}

if(preg_match_all("/^register_user$/ui", $_GET['type'])){
    if(!isset($_GET['login'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр login!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['password'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр password!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "INSERT INTO `users`(`login`, `password`) VALUES ('".$_GET['login']."','".$_GET['password']."')";
    $res_query = mysqli_query($connection,$query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", 
            "Ошибка в запросе!",
            true,
            "ERROR",
            null
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", 
        "Пользователь зарегестрирован!",
        false,
        "SUCCESS"
    );
    exit();
}

else if(preg_match_all("/^login_user$/ui", $_GET['type'])){
    if(!isset($_GET['login'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр login!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['password'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр password!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "SELECT COUNT(id) > 0 AS `RESULT` FROM `users` WHERE `login`='".$_GET['login']."' AND `password`='".$_GET['password']."' ";
    $res_query = mysqli_query($connection,$query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", 
            "Ошибка в запросе!",
            true,
            "ERROR",
            null
        );
        exit();
    }

    $res = mysqli_fetch_assoc($res_query);
    if($res["RESULT"] == "0"){
        echo ajax_echo(
            "Ошибка!", 
            "Пользователь отсутствует!",
            true,
            "ERROR",
            null
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", 
        "Пользователь авторизирован!",
        false,
        "SUCCESS"
    );
    exit();
}

else if(preg_match_all("/^replenish_balance$/ui", $_GET['type'])){
    if(!isset($_GET['login'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр login!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['sum'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр sum!",
            "ERROR",
            null
        );
        exit;
    }

    $query = "UPDATE `users` SET `balance`= ".$_GET['sum']." + (SELECT `balance` WHERE `login` = '".$_GET['login']."') WHERE login = '".$_GET['login']."'";
    $res_query = mysqli_query($connection,$query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", 
            "Ошибка в запросе!",
            true,
            "ERROR",
            null
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", 
        "Баланс пополнен!",
        false,
        "SUCCESS"
    );
    exit();
}

else if(preg_match_all("/^change_user_name$/ui", $_GET['type'])){
    if(!isset($_GET['login'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр login!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['name'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр name!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "UPDATE `users` SET `name`= '".$_GET['name']."' WHERE login = '".$_GET['login']."'";
    $res_query = mysqli_query($connection,$query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", 
            "Ошибка в запросе!",
            true,
            "ERROR",
            null
        );
        exit();
    }

    echo ajax_echo(
        "Успех!", 
        "Имя изменено!",
        false,
        "SUCCESS"
    );
    exit();
}

else if(preg_match_all("/^list_accounts$/ui", $_GET['type'])){
    $query = "SELECT `id`,`title`,`description`,`price` FROM `accounts` WHERE `deleted`=false AND `is_bought`=false";
    $res_query = mysqli_query($connection,$query);

    if(!$res_query){
        echo ajax_echo(
            "Ошибка!", 
            "Ошибка в запросе!",
            true,
            "ERROR",
            null
        );
        exit();
    }

    $arr_res = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++){
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_res, $row);
    }
    echo ajax_echo(
        "Успех!", 
        "Список аккаунтов!",
        false,
        "SUCCESS",
        $arr_res
    );
    exit();
}

else if(preg_match_all("/^add_account$/ui", $_GET['type'])){
    if(!isset($_GET['title'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр title!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['price'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр price!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['data'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр data!",
            "ERROR",
            null
        );
        exit;
    }

    $desc='null';
    if(isset($_GET['description'])){
        $desc = "'".$_GET['description']."'";
    }

    $query = "INSERT INTO `accounts`(`title`, `price`, `data`,`description`) VALUES ('".$_GET['title']."', ".$_GET['price'].", '".$_GET['data']."', ".$desc.")";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }
    
    echo ajax_echo(
        "Успех!",
        "Новый аккаунт был добавлен в базу данных!",
        false,
        "SUCCESS"
    );
    exit;
}

else if(preg_match_all("/^buy_account$/ui", $_GET['type'])){
    if(!isset($_GET['userid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр userid!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['accountid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр accountid!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "SELECT `balance`>(SELECT `price` FROM `accounts` WHERE `accounts`.`id` = ".$_GET['accountid'].") AS 'RESULT' FROM `users` WHERE `id`=".$_GET['userid'];

    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }

    $res = mysqli_fetch_assoc($res_query);
    if($res["RESULT"] == "0"){
        echo ajax_echo(
            "Ошибка!", 
            "Не достаточно средств!",
            true,
            "ERROR",
            null
        );
        exit();
    }


    $query = "INSERT INTO `purchase_history`(`userid`, `accountid`) VALUES (".$_GET['userid'].", ".$_GET['accountid'].")";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе2!",
            true,
            null
        );
        exit;
    }
    
    echo ajax_echo(
        "Успех!",
        "Товар был куплен!",
        false,
        "SUCCESS"
    );
    exit;
}

else if(preg_match_all("/^add_to_cart$/ui", $_GET['type'])){
    if(!isset($_GET['userid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр userid!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['accountid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр accountid!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "INSERT INTO `cart`(`userid`, `accountid`) VALUES (".$_GET['userid'].", ".$_GET['accountid'].")";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }
    
    echo ajax_echo(
        "Успех!",
        "Товар был добавлен в карзину!",
        false,
        "SUCCESS"
    );
    exit;
}

else if(preg_match_all("/^remove_from_cart$/ui", $_GET['type'])){
    if(!isset($_GET['userid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр userid!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['accountid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр accountid!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "UPDATE `cart` SET `deleted`=true WHERE `userid`=".$_GET['userid']." AND `accountid`=".$_GET['accountid'];
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }
    
    echo ajax_echo(
        "Успех!",
        "Товар был удален из карзины!",
        false,
        "SUCCESS"
    );
    exit;
}

else if(preg_match_all("/^list_cart$/ui", $_GET['type'])){
    if(!isset($_GET['userid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр userid!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "SELECT `title`, `description`, `price` from `accounts` WHERE `id` IN (SELECT `accountid` FROM `cart` WHERE `userid`=".$_GET['userid']." AND `deleted`=false)";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }

    $arr_res = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++){
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_res, $row);
    }
    
    echo ajax_echo(
        "Успех!",
        "Товары были выведены!",
        false,
        "SUCCESS",
        $arr_res
    );
    exit;
}

else if(preg_match_all("/^add_game$/ui", $_GET['type'])){
    if(!isset($_GET['title'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр userid!",
            "ERROR",
            null
        );
        exit;
    }
    $desc='null';
    if(isset($_GET['description'])){
        $desc = "'".$_GET['description']."'";
    }

    $query = "INSERT INTO `games`(`title`, `description`) VALUES ('".$_GET['title']."', ".$desc.")";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }
    
    echo ajax_echo(
        "Успех!",
        "Игра добавлена!",
        false,
        "SUCCESS"
    );
    exit;
}

else if(preg_match_all("/^attach_game$/ui", $_GET['type'])){
    if(!isset($_GET['gameid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр gameid!",
            "ERROR",
            null
        );
        exit;
    }
    if(!isset($_GET['accountid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр accountid!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "INSERT INTO `games_in_account`(`accountid`, `gameid`) VALUES (".$_GET['accountid'].",".$_GET['gameid'].")";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }
    
    echo ajax_echo(
        "Успех!",
        "Игра добавлена!",
        false,
        "SUCCESS"
    );
    exit;
}

else if(preg_match_all("/^list_acc_games$/ui", $_GET['type'])){
    if(!isset($_GET['accountid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр accountid!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "SELECT `title`, `description` from `games` WHERE `id` IN (SELECT `gameid` FROM `games_in_account` WHERE `accountid`=".$_GET['accountid']." AND `deleted`=false)";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }

    $arr_res = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++){
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_res, $row);
    }
    
    echo ajax_echo(
        "Успех!",
        "Товары были выведены!",
        false,
        "SUCCESS",
        $arr_res
    );
    exit;
}

else if(preg_match_all("/^remove_user$/ui", $_GET['type'])){
    if(!isset($_GET['userid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр userid!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "UPDATE `users` SET `deleted`=true WHERE `id` = ".$_GET['userid'];
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }
    
    echo ajax_echo(
        "Успех!",
        "Пользователь удалён!",
        false,
        "SUCCESS"
    );
    exit;
}

else if(preg_match_all("/^purchase_history$/ui", $_GET['type'])){
    if(!isset($_GET['userid'])){
        echo ajax_echo(
            "Ошибка!",
            "Вы не указали GET параметр userid!",
            "ERROR",
            null
        );
        exit;
    }
    $query = "SELECT `title`,`description`,`price`,`data` FROM `accounts` WHERE `id` IN (SELECT `accountid` FROM `purchase_history` WHERE `userid`=".$_GET['userid'].")";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }
    
    $arr_res = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++){
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_res, $row);
    }

    echo ajax_echo(
        "Успех!",
        "Список покупок!",
        false,
        "SUCCESS",
        $arr_res
    );
    exit;
}

else if(preg_match_all("/^list_games$/ui", $_GET['type'])){

    $query = "SELECT `title`, `description` from `games` WHERE `deleted`=false";
    
    $res_query = mysqli_query($connection, $query);
    
    if(!$res_query){
        echo ajax_echo(
            "Ошибка!",
            "Ошибка в запросе!",
            true,
            null
        );
        exit;
    }

    $arr_res = array();
    $rows = mysqli_num_rows($res_query);

    for ($i=0; $i < $rows; $i++){
        $row = mysqli_fetch_assoc($res_query);
        array_push($arr_res, $row);
    }
    
    echo ajax_echo(
        "Успех!",
        "Товары были выведены!",
        false,
        "SUCCESS",
        $arr_res
    );
    exit;
}