<?php
	$link = mysqli_connect ("localhost", "root","dima8546",'users');//пишите свои настройки
	mysqli_query($link, 'set names cp1251');
	error_reporting(E_ALL); 
	ini_set("display_errors", 1);
	session_start();//не забываем во всех файлах писать session_start
if (isset($_POST['login']) && isset($_POST['password'])){
    //немного профильтруем логин
	$login = mysqli_real_escape_string($link,htmlspecialchars($_POST['login']));
    //хешируем пароль т.к. в базе именно хеш
	$password = md5(trim($_POST['password']));
     // проверяем введенные данные
    $query = "SELECT user_id, user_login
            FROM user
            WHERE user_login= '$login' AND user_password = '$password'
            LIMIT 1";
    $sql = mysqli_query($link,$query) or die(mysqli_error());
    // если такой пользователь есть
    if (mysqli_num_rows($sql) == 1) {
        $row = mysqli_fetch_assoc($sql);
		//ставим метку в сессии 
		$_SESSION['user_id'] = $row['user_id'];
		$_SESSION['user_login'] = $row['user_login'];
		//ставим куки и время их хранения 10 дней
		setcookie("CookieMy", $row['user_login'], time()+60*60*24*10);
   }
    else {
		echo("Такого пользователя не существует, или введен неправильный логин/пароль.");
		sleep(10);
        //если пользователя нет, то пусть пробует еще
		header("Location: index.html"); 
    }
}
//проверяем сессию, если она есть, то значит уже авторизовались
if (isset($_SESSION['user_id'])){
	echo htmlspecialchars($_SESSION['user_login'])." <br />"."Вы авторизованы <br />
	Т.е. мы проверили сессию и можем открыть доступ к определенным данным";
	header("Location: ../UserPage/main.php");//куда перейти
} else {
	$login = '';
	//проверяем куки, может он уже заходил сюда
	if (isset($_COOKIE['CookieMy'])){
		$login = htmlspecialchars($_COOKIE['CookieMy']);
	}
/*	print <<< 	html
<form action="mylogin.php" method="POST">
		Логин <input name="login" type="text" value = $login><br>
		Пароль <input name="password" type="password"><br>
		<input name="submit" type="submit" value="Войти">
	</form>
html;*/
}
?>