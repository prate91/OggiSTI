<?php
$id_evento = isset($_POST["id_evento"]) ? $_POST['id_evento'] : '';
$var = isset($_POST["date"]) ? $_POST['date'] : '';
$date = str_replace('/', '-', $var);
$dateCorr = date('Y-m-d', strtotime($date));
$title_ita = isset($_POST["title_ita"]) ? $_POST['title_ita'] : '';
$title_eng = isset($_POST["title_eng"]) ? $_POST['title_eng'] : '';
$abstr_ita = isset($_POST["abstr_ita"]) ? $_POST['abstr_ita'] : '';
$abstr_eng = isset($_POST["abstr_eng"]) ? $_POST['abstr_eng'] : '';
$desc_ita = isset($_POST["desc_ita"]) ? $_POST['desc_ita'] : '';
$desc_eng = isset($_POST["desc_eng"]) ? $_POST['desc_eng'] : '';
$riferimenti = isset($_POST["riferimenti"]) ? $_POST['riferimenti'] : '';
$keywords = isset($_POST["keywords"]) ? $_POST['keywords'] : '';
$linkImg = isset($_POST["vecchiaImmagine"]) ? $_POST['vecchiaImmagine'] : '';
$fonte_img = isset($_POST["fonte_img"]) ? $_POST['fonte_img'] : '';
$autore = isset($_POST["autore"]) ? $_POST['autore'] : '';
$stato = isset($_POST["stato"]) ? $_POST['stato'] : '';
$salvato = isset($_POST["salvato"]) ? $_POST['salvato'] : '';
$verifica1 = isset($_POST["Iapprovazione"]) ? $_POST['Iapprovazione'] : '';
$verifica2 = isset($_POST["IIapprovazione"]) ? $_POST['IIapprovazione'] : '';

$_SESSION['id_evento'] = $id_evento;    
$_SESSION['data_evento'] = $var;
$_SESSION['titolo_ita'] = $title_ita = mysql_escape_string($title_ita);
$_SESSION['titolo_eng'] = $title_eng = mysql_escape_string($title_eng);
$_SESSION['abstr_ita'] = $abstr_ita = mysql_escape_string($abstr_ita);
$_SESSION['abstr_eng'] = $abstr_eng = mysql_escape_string($abstr_eng);
$_SESSION['desc_ita'] = $desc_ita = mysql_escape_string($desc_ita);
$_SESSION['desc_eng'] = $desc_eng = mysql_escape_string($desc_eng);
$_SESSION['keywords'] = $keywords = mysql_escape_string($keywords);
$_SESSION['riferimenti'] = $riferimenti = mysql_escape_string($riferimenti);
$_SESSION['fonte_img'] = $fonte_img = mysql_escape_string($fonte_img);
$_SESSION['immagine'] = $linkImg;
$autore = htmlentities($autore);
$verifica1 = htmlentities($verifica1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

echo '$_FILES["immagine"]["name"]'."<br/>";
echo $_FILES["immagine"]["name"];
echo '$_FILES["immagine"]["tmp_name"]'."<br/>";
echo $_FILES["immagine"]["tmp_name"];

echo "<br/>";

$check = $_FILES["immagine"]["name"];

if($check==""){
	echo "VUOTO";
}else{
	echo "PIENO";
}
 
  
}

?>