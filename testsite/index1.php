<!doctype html>
<html>
<head>
<meta charset="utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title> Тестовая страница</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>
<body>
	<table class="table" align="center" width="50%" bordercolor="black" border="1">
	<tr align="center" height="30">
					<th>ФИО</th>
					<th>Должность</th>
					<th>Дата приема на работу</th>
					<th>Дата увольнения</th>
					<th>Причина увольнения</th>
					<th>Размер ЗП</th>
					<th>Начальник</th>
			</tr>
<?php
		//Устанавливаем доступы к базе данных:
			$host = 'localhost'; //имя хоста, на локальном компьютере это localhost
			$user = 'user'; //имя пользователя, по умолчанию это root
			$password = 'test'; //пароль, по умолчанию пустой
			$db_name = 'testdb'; //имя базы данных

		//Соединяемся с базой данных используя наши доступы:
			$link = mysqli_connect($host, $user, $password, $db_name);

		//Устанавливаем кодировку (не обязательно, но поможет избежать проблем):
			mysqli_query($link, "SET NAMES 'utf8'");
		
		/*
		    $kol - количество записей для вывода
		    $art - с какой записи выводить
		    $total - всего записей
		    $page - текущая страница
		    $prev - предыдущая страница
		    $next - следующая страница
		    $str_pag - количество страниц для пагинации
		    */

		// Пагинация
		// Текущая страница
		    if (isset($_GET['page']))
		    {
		        $page = $_GET['page'];
		    }
		    else 
		    {
		        $page = 1;
		    };

		    $prev = ($page - 1);
		    $next = ($page + 1);

		    $kol = 10;  // количество записей для вывода
		    $art = ($page * $kol) - $kol;

		    $query = "SELECT user.id, last_name, first_name, middle_name, position.name, user.created_at, position.salary, department.leader_id
					FROM user_position
                    JOIN user ON user_position.user_id = user.id
                    JOIN department ON department.id = user_position.department_id
                    JOIN position ON position.id = user_position.position_id
					GROUP BY user.id ASC
					LIMIT $art, $kol";

		// Определяем все количество записей в таблице
		    $res = $link->query($query);
		    $row = $res->fetch_row();
		    $total = $row[0];			

		// Количество страниц для пагинации
					    $str_pag = ceil($total / $kol);

		// Запрос и вывод записей
		//Делаем запрос к БД, результат запроса пишем в $result:
		      	$result = mysqli_query($link, $query) or die(mysqli_error($link));
		        //$row = mysqli_fetch_row($result);
		        while ($row  =  mysqli_fetch_row($result))
							{
								echo '<tr align="center">';
    							echo '<td>' . $row[1] . " " . $row[2] . " " . $row[3] . '</td>';
								echo '<td>' . $row[4] . '</td>';
								echo '<td>' . $row[5] . '</td>';
								echo '<td>' . " - " . '</td>';
								echo '<td>' . " - " . '</td>';
								echo '<td>' . $row[6] . '</td>';
								echo '<td>' . $row[7] . '</td>';
								echo '</tr>';
							}
	echo '</table>';

		// формируем пагинацию
			echo '<table align="center" width="25%">';
			echo '<tr align="center">';
		    echo "<td><a href=index1.php?page=" . $prev . "> <<< </a></td>";
		    echo '<td>' . $page . '</td>';
		    echo "<td><a href=index1.php?page=" . $next . "> >>> </a></td>";
		    echo '</tr>';
		    echo '</table>';
?>
</body>
</html>