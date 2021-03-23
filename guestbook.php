<?php
if (!empty($_POST['message'])) {
    $user = strip_tags($_POST["name"]);
    $email = strip_tags($_POST["email"]);
    $subj = strip_tags($_POST["subject"]);
    $mess = strip_tags($_POST["message"]);
    $md5 = $_POST["code"];
    $co = md5($_POST["codeorig"]);
    $id = $_SERVER['REMOTE_ADDR'];
    $date = date("d.m.y-H:i", $_SERVER['REQUEST_TIME']);
} else {
    echo "Вы не заполнили сообщение!";
    exit();
}
/*
*подсчёт колич записей в гост. книгу
*/
function number()
{
    $idxName = "message/idx.txt";
    $idx = file_get_contents($idxName);
    if ($idx == '') {
        $idx = 1;
    } else $idx = ++$idx;
    $numtxt = fopen($idxName, "w");
    fwrite($numtxt, $idx);
    fclose($numtxt);
}

if ($md5 == $co) {
    $tr_name = trim($user);
    $tr_subj = trim($subj);
    $tr_mess = trim($mess);
    $datawin = "ID: $id \nДата: $date <br/>\nИмя: $tr_name <br/>\nПочта: $email  <br/>\nТема: $tr_subj <br/>\nСообщение: $tr_mess <br/>\n" . PHP_EOL;
    $datafil = strip_tags($datawin);

    // антифлуд начало
    $fileKarantin = "message/karantin.txt";
    $blListName = "message/blacklist.txt";
    $fileName = "message/message.txt";
    $idDateTime = $id . " " . $date;
    $idDate = substr($idDateTime, 0, -6);
    $karantin = file_get_contents($fileKarantin);
    $blacklist = file_get_contents($blListName);
    $masBlList = explode("\n", $blacklist);
    /*
    *ф-я для записи в файл сообщение
    */
    function write($name, $data)
    {
        $txt = fopen($name, "at");
        fwrite($txt, $data);
        fclose($txt);
    }

    if (in_array($idDateTime, $masBlList) == false) {//проверка id и data в блеклисте
        if ($karantin == " ") {
            file_put_contents($fileKarantin, $idDateTime);
            write($fileName, $datafil);
            number();
            echo "Ваша запись добавлена 1";
        } else {
            $read = fopen($fileKarantin, "r");
            while ($str = fgets($read)) {
                $mas[] = explode(" ", $str);
            }
            //провереям добавлялся ли такой id за эту минуту или нет
            foreach ($mas as $key => $val) {
                if (in_array($id, $val) and in_array(date("d.m.y-H:i"), $val)) {
                    fclose($read);
                    $karantin = substr($karantin, 0, -6);
                    $dataBlack = $karantin . "\n\r";
                    write($blListName, $dataBlack);
                    file_put_contents($fileKarantin, " ");
                    die("Вас занесено в black list за спам!!!");
                } else {
                    //удалить запись из карантина с посл. id и записать сообщение в гост. книгу
                    file_put_contents($fileKarantin, " ");
                    write($fileName, $datafil);
                    number();
                    file_put_contents($fileKarantin, $idDateTime);
                    echo "Ваша запись добавлена 2";
                }
            }
        }
    } else {
        die("Вас занесено в black list за спам!!!");
    }
} else
    echo "Не правильно введён код с картинки!";

