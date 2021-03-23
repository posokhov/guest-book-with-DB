<!DOCTYPE html>
<html>
<head>
    <title>Гостевая книга</title>
    <link rel="stylesheet" href="css/index.css">
    <meta charset="utf-8"/>
</head>
<body>
<?php
$fileName = "message/message.txt";
$text = file_get_contents($fileName);
$clear = trim($text);//убираем пробелы
$a = explode("\n", $clear);// разбиваем строку на масив
$newmas = array_chunk($a, 7);//разбиваем массив на многомерный массив
$newmas1 = array_reverse($newmas);//меняем порядок сконца на начало
$mas_count = count($newmas1);//длина масива
$limit = 5;
$total = intval(($mas_count - 1) / $limit) + 1;// общее кол стр
for ($i = 0; $i < count($newmas1); $i++) {
    $temp[] = implode("", $newmas1[$i]);
}

unset($newmas1);
$page = $_GET['page'];

if (($page < 1) or ($page == "")) {
    $page = 1;
}
if ($page > $total) {
    $page = $total;
}
$page = intval($page);
$start = $page * $limit - $limit; // вычисляем с какой стр начинать показ
$end = $page * $limit;// закончить
for ($i = $start; $i < $end; $i++) {
    echo $temp[$i] . "<br/>";// вывод
}

if ($page != 1) {
    $pervpage = '<a href=admin.php?page=1><<</a> <a href=admin.php?page=' . ($page - 1) . '><</a> ';
}
// Проверяем нужны ли стрелки вперед
if ($page != $total) {
    $nextpage = ' <a href=admin.php?page=' . ($page + 1) . '>> </a> <a href=admin.php?page=' . $total . '>>></a>';
}
// Находим две ближайшие станицы с обоих краев
if ($page - 2 > 0) {
    $page2left = ' <a href=admin.php?page=' . ($page - 2) . '>' . ($page - 2) . '</a> | ';
}
if ($page - 1 > 0) {
    $page1left = '<a href=admin.php?page=' . ($page - 1) . '>' . ($page - 1) . '</a> | ';
}
if ($page + 2 <= $total) {
    $page2right = ' | <a href=admin.php?page=' . ($page + 2) . '>' . ($page + 2) . '</a>';
}
if ($page + 1 <= $total) {
    $page1right = ' | <a href=admin.php?page=' . ($page + 1) . '>' . ($page + 1) . '</a>';
}
// Вывод меню
echo '<div class="nav">' . $pervpage . $page2left . $page1left . '<b>' . $page . '</b>' . $page1right . $page2right . $nextpage . '</div>';
?>