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
	<table align="center" width = 50%>
		<form method="POST">
		<tr align = "center">
			<td><input type="radio" name="radio" value="radio1" /></td>
    		<td><input type="radio" name="radio" value="radio2" /></td>
    		<td><input type="radio" name="radio" value="radio3" /></td>
		</tr>
		<tr align="center">
			<td>Испытательный срок</td>
			<td>Уволенные</td>
			<td>Последние принятые</td>
		</tr>
		<tr align="center">
			<td colspan="3"><input type="submit" value="Отправить"></td>
		</tr>
		</form>
	</table>

	<table class="table" align="center" width="50%" bordercolor="black" border="1">
	<tr><h3></h3></tr>
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

	//Формируем тестовый запрос:
		$query1 = "SELECT user.id, last_name, first_name, middle_name, position.name, user.created_at, position.salary, department.leader_id
					FROM user_position
                    JOIN user ON user_position.user_id = user.id
                    JOIN department ON department.id = user_position.department_id
                    JOIN position ON position.id = user_position.position_id
                    WHERE DATEDIFF (CURRENT_DATE, user.created_at)<90
					GROUP BY user.id ASC";
		
		$query2 = "SELECT last_name, first_name, middle_name, position.name, user.created_at, user_dismission.update_at, dismission_reason.description, position.salary, department.leader_id
					FROM user_position
					JOIN user ON user_position.user_id = user.id
					JOIN user_dismission ON user_dismission.user_id = user.id
					JOIN dismission_reason ON dismission_reason.id = user_dismission.reason_id
					JOIN department ON department.id = user_position.department_id
					JOIN position ON position.id = user_position.position_id";

		$boss = "SELECT department.id
				FROM user_position, position, user, department
				WHERE position.id = user_position.position_id AND user_position.user_id = user.id AND department.leader_id = position.id AND position.name LIKE 'Начальник%'
				GROUP BY department.id ASC";
		
	//Делаем запрос к БД, результат запроса пишем в $result:
		$result1 = mysqli_query($link, $query1) or die(mysqli_error($link));
		$result2 = mysqli_query($link, $query2) or die(mysqli_error($link));
		$resultboss = mysqli_query($link, $boss) or die(mysqli_error($link));

		if(isset($_POST["radio"]))
			{
    			switch($_POST["radio"])
				{
					case radio1:
						while ($row  =  mysqli_fetch_row($result1))
							{
								$bossname = "SELECT last_name, first_name
												FROM user_position
												JOIN user ON user_position.user_id = user.id
												JOIN department ON user_position.department_id = department.id
												JOIN position ON user_position.position_id = position.id
												WHERE department.id = $row[7] and position.name LIKE \"Начальник%\" ";
								$resultbossname = mysqli_query($link, $bossname) or die(mysqli_error($link));
								$rowname = mysqli_fetch_row($resultbossname);
								echo '<tr align="center">';
    							echo '<td>' . $row[1] . " " . $row[2] . " " . $row[3] . '</td>';
								echo '<td>' . $row[4] . '</td>';
								echo '<td>' . $row[5] . '</td>';
								echo '<td>' . " - " . '</td>';
								echo '<td>' . " - " . '</td>';
								echo '<td>' . $row[6] . '</td>';
								echo '<td>' . $rowname[0] . " " . $rowname[1] . '</td>';
								echo '</tr>';
							}
						break;

					case radio2:
						while ($row  =  mysqli_fetch_row($result2))
							{
								$bossname = "SELECT last_name, first_name
												FROM user_position
												JOIN user ON user_position.user_id = user.id
												JOIN department ON user_position.department_id = department.id
												JOIN position ON user_position.position_id = position.id
												WHERE department.id = $row[8] and position.name LIKE \"Начальник%\" ";
								$resultbossname = mysqli_query($link, $bossname) or die(mysqli_error($link));
								$rowname = mysqli_fetch_row($resultbossname);
								echo '<tr align="center">';
    							echo '<td>' . $row[0] . " " . $row[1] . " " . $row[2] . '</td>';
    							echo '<td>' . $row[3] . '</td>';
								echo '<td>' . $row[4] . '</td>';
								echo '<td>' . $row[5] . '</td>';
								echo '<td>' . $row[6] . '</td>';
								echo '<td>' . $row[7] . '</td>';
								echo '<td>' . $rowname[0] . " " . $rowname[1] . '</td>';
								echo '</tr>';
							}
						break;

					case radio3:
						while ($bossrow  =  mysqli_fetch_row($resultboss))
							{
								$query3 = "SELECT last_name, first_name, middle_name, department.description, position.name, user.created_at, position.salary, department.leader_id
											FROM user_position, position, department, user
											WHERE user_position.position_id = position.id AND department.id = user_position.department_id AND user_position.user_id = user.id AND department.id = $bossrow[0]
											GROUP BY user.created_at DESC";
								$bossname = "SELECT last_name, first_name
											FROM user_position
											JOIN user ON user_position.user_id = user.id
											JOIN department ON user_position.department_id = department.id
											JOIN position ON user_position.position_id = position.id
											WHERE department.id = $bossrow[0] and position.name LIKE \"Начальник%\" ";
								$result3 = mysqli_query($link, $query3) or die(mysqli_error($link));
								$resultbossname = mysqli_query($link, $bossname) or die(mysqli_error($link));
								$row = mysqli_fetch_row($result3);
								$rowname = mysqli_fetch_row($resultbossname);
								echo '<tr align="center">';
    							echo '<td>' . $row[0] . " " . $row[1] . " " . $row[2] . '</td>';
    							echo '<td>' . $row[4] . '</td>';
    							echo '<td>' . $row[5] . '</td>';
    							echo '<td>' . " - " . '</td>';
    							echo '<td>' . " - " . '</td>';
    							echo '<td>' . $row[6] . '</td>';
    							echo '<td>' . $rowname[0] . " " . $rowname[1] . '</td>';
								echo '</tr>';
							}
						break;
				}
			}
	'</table>'
?>
</body>
</html>