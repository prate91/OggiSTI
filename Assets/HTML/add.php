<?php
    include("../api/config.php");
    session_start();
    if(!isset($_SESSION['login_user'])) {
        header('Location: no_login.php?error=inv_access');
    }
    $autore = $_SESSION['login_user'];
    $nome_utente =  $_SESSION['nome_utente'];
    $cognome_utente =  $_SESSION['cognome_utente'];
    $nome_completo = $_SESSION['nome_completo'];
    $amministratore = $_SESSION['amministratore'];
    $redattore = $_SESSION['redattore'];
    $revisore = $_SESSION['revisore'];
      if($redattore==0) {
        header('Location: no_permessi.php');
    }
    $autori="";
    $messaggio = $mess = $errore = $classe = "";
    $prev="";
     $id_evento = $dateCorr = $titolo_ita = $titolo_eng = $abstr_ita = $abstr_eng = $immagine = $desc_ita = $desc_eng = $riferimenti = $keywords = $fonte_img = $commento = "";
     if(isset($_GET["id_evento"])){
            $menuEvento = "Modifica evento";
            $id_evento = $_GET["id_evento"];
            $sql = "SELECT * FROM eventiappr WHERE id_evento = '$id_evento'";
            $result = mysqli_query($OggiSTI_conn_adm,$sql);
            $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
            $oldDate = $row["data_evento"];
            $date = date('d-m-Y', strtotime($oldDate));
            $dateCorr = str_replace('-', '/', $date);
            $titolo_ita = $row["titolo_ita"]; 
            $titolo_eng = $row["titolo_eng"];
            $abstr_ita = $row["abstr_ita"];
            $abstr_eng = $row["abstr_eng"];
            $immagine = $row["immagine"];
            $fonte_img = $row["fonteimmagine"];
            $desc_ita = $row["desc_ita"];
            $desc_eng = $row["desc_eng"];
            $riferimenti = $row["riferimenti"];
            $keywords = $row["keywords"];
            $autori = $row["redattore"];
            $commento = $row["commento"];
            $autoreMatch="/".$nome_completo."/i";
            if (preg_match($autoreMatch, $autori)) {
                $autori = $autori;
            }else{
                $autori = $autori.", ".$nome_completo;
            }
        }else{
             $menuEvento = "Aggiungi evento";
             $sql = "INSERT INTO eventiappr (titolo_ita) VALUES ('')";
             mysqli_query($OggiSTI_conn_adm, $sql);    
             $risultato = mysqli_query($OggiSTI_conn_adm, "SELECT MAX(id_evento) FROM eventiappr");
             $riga = mysqli_fetch_array($risultato,MYSQLI_ASSOC);
            $id_evento = $riga["MAX(id_evento)"];
            $autori=$nome_completo;
        }

                          

        if(isset($_GET["messaggio"])){
            $mess=$_GET["messaggio"];
            if($mess=="salva"){
                $messaggio="Evento salvato correttamente";
                $classe="alert alert-success";
            }
            if($mess=="modifica"){
                $messaggio="Stai modificando un evento";
                $classe="alert alert-warning";
            }
            if($mess=="errore"){
                $messaggio="Evento NON salvato";
                $classe="alert alert-danger";
            }
            
        }else{
            $messaggio="Hai creato un nuovo evento";
            $classe="alert alert-warning";
        }

        if(isset($_GET["preview"])){
            $prev = $_GET["preview"];
            if($prev=="ok"){
                $link = "<script>window.open(\"../PHP/OggiSTI_preview.php?id_evento=$id_evento&id_state=Preview\", \"previewOggiSTI\", \"width=864,height=1000\")</script>";
                echo $link;       
                
            }
        }
    

?>

<!DOCTYPE html>
<html lang='it'>
<head>
<meta charset='utf-8'>
<link rel="icon" type="image/png" href="../img/HMR-Icon16x16.png" />
<link rel='stylesheet' href='../css/bootstrap.css'>
<link rel='stylesheet' href='../../../HMR_Style.css'>
<link rel='stylesheet' href='../css/dcalendar.picker.css'>
<script src='../js/jquery-3.2.0.min.js'></script>
<script src='../js/bootstrap.js'></script>
<script src='../js/dcalendar.picker.js'></script>
<script src="https://www.w3schools.com/lib/w3.js"></script>
<script src="../tinymce/jquery.tinymce.min.js"></script>
<script src="../tinymce/tinymce.min.js"></script>
<script src='../js/javascript.js'></script>
 <script type='text/javascript' src='../../../Assets/JS/HMR_CreaHTML.js'></script>




<title>Oggi nella storia dell'informatica - HMR</title>
</head>
<body>
<!-- header -->
<div class="HMR_Banner">
    <script> creaHeader(3, 'HMR_2017g_GC-WebHeaderRite-270x105-3.png') </script>
</div>

<div id="HMR_Menu" class="HMR_Menu" >
    <script> creaMenu(3, 5) </script>
</div>
	
    <span class="stop"></span>
    
    <!--content-->
	<div class="oggiSTI_content_amm">
    <!-- Navbar -->
	<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
		<button	type="button" class= "navbar-toggle" data-toggle="collapse" data-target="#nav-toggle">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="#"><img id="logoHMR" src="../img/logo.png" alt="LOGO HMR"/></a>
	</div>
	<div class="collapse navbar-collapse" id="nav-toggle">
	<ul class="nav	navbar-nav">
		<li><a href="ammOggiSTI.php">Home</a></li>
		<li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Eventi
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="listaEventiSalvati.php">Salvati</a></li>
          <li><a href="listaEventiRedazione.php">In redazione</a></li>
          <li><a href="listaEventiApprovazione.php">In approvazione</a></li>
          <li><a href="listaEventi.php">Pubblicati</a></li>
        </ul>
      </li>
		<li class="active"><a href="#"><?php echo $menuEvento; ?></a></li>
	</ul>
	<form class="navbar-form navbar-right" role="search">
        <div class="text-right iconaUser"><a href="welcome.php"><span class="glyphicon glyphicon-user"></span> <?php echo $autore; ?></a></div>
		</form>
	</div>
	</nav>
            
         <div id="visualizzaCommento" class="alert alert-info">
        <strong>Commento:</strong> <?php echo $commento; ?>
        </div>
    
        <!-- Form -->
		<div class="jumbotron">
        
        <div class="<?php echo $classe; ?>" id="alertEvento">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <p><?php echo $messaggio; ?></p>
        </div>
        
       
            
            
		<form id="addEvent" method="post" action="../Api/inserisciEvento.php" enctype="multipart/form-data">
        <!-- Campo ID -->
        <label for="id_evento">Id evento</label>
		<input type="text" name="id_evento" class="form-control" id="id_evento" readonly value="<?php if($mess=="errore"){echo $_SESSION['id_evento'];}else{ echo $id_evento;} ?>">
        <br/>
		<!-- Inserimento data -->
		<div id="formData" class="form-group">
		<label for="date">Data dell'evento</label>
		<input type="datetime" name="date" class="form-control" id="date" placeholder="dd/mm/yyyy" value="<?php if($mess=="errore"){echo $_SESSION['data_evento'];}else{echo $dateCorr;} ?>">
		<span id="glyphiconDate"></span>
		<span id="helpDate" class="help-block"></span>
		</div>
		<br/>
		<!-- Form per il titolo -->
		<div id="formTitle_ita" class="form-group">
		<label for='title_ita'>Titolo in italiano</label>
        <a href="#" data-toggle="tooltip" title="Il titolo non può superare i 140 caratteri. La dimensione consigliata è di 70 caratteri su 2 righe.
                                                 Usare un titolo ad effetto per incuriosire il visitatore, mettendo maggiori dettagli nella descrizione estesa"><span class="glyphicon glyphicon-info-sign"></span></a>
		<textarea name="title_ita" class="form-control" rows="2" id="title_ita"><?php if($mess=="errore"){echo $_SESSION['titolo_ita'];}else{ echo $titolo_ita;} ?></textarea>
		<span id="glyphiconTitleIta"></span>
		<span id="countBox_title_ita" class="help-block pull-right">140</span>
		<span id="helpTitleIta" class="help-block">La dimensione massima consigliata è di 70 caratteri</span>
		</div>
		<br/>
		<div id="formTitle_eng" class="form-group">
		<label for='title_eng'>Title in English</label>
        <a href="#" data-toggle="tooltip" title="Title must not contain more than 140 characters. The recommended size is 70 characters on 2 lines. You can use an effect title for excite curiosity and specify more information in long description"><span class="glyphicon glyphicon-info-sign"></span></a>
		<textarea name="title_eng" class="form-control" rows="2" id="title_eng"><?php if($mess=="errore"){echo $_SESSION['titolo_eng'];}else{ echo $titolo_eng;} ?></textarea>
		<span id="glyphiconTitleEng"></span>
		<span id="countBox_title_eng" class="help-block pull-right">140</span>
		<span id="helpTitleEng" class="help-block">The maximum recommended size is 70 characters</span>
		</div>

		<!-- Immagine -->
        <br/>
        <img id="oggiSTI_immagineEvento" src="<?php if($mess=="errore"){echo "../".$_SESSION['immagine'];}else{ echo "../".$immagine;} ?>" alt="Nessuna immagine precedente"/>
        <input type="text" name="vecchiaImmagine" class="form-control" id="vecchiaImmagine" readonly value="<?php if($mess=="errore"){echo $_SESSION['immagine'];}else{ echo $immagine;} ?>">
		<br/>
		<label class="custom-file">Immagine</label>
        <a href="#" data-toggle="tooltip" title="Caricare un'immagine di dimensioni <5MB"><span class="glyphicon glyphicon-info-sign"></span></a>
		<input type="file" name="immagine" id="immagine" class="custom-file-input"/>
        <!-- <div id="image-holder"></div> -->
		<!-- <span class="custom-file-control"></span> -->
        <br/>
        <!-- Fonte Immagine -->
        <label for='abstr_ita'>Fonte dell'immagine</label>
        <a href="#" data-toggle="tooltip" title="Insere la fonte dell'immagine"><span class="glyphicon glyphicon-info-sign"></span></a>
		<textarea name="fonte_img" class="form-control" rows="1" id="fonte_img"><?php if($mess=="errore"){echo $_SESSION['fonte_img'];}else{ echo $fonte_img;} ?></textarea>
		<br/>
		<!-- Icona 
		<br/>
		<label class="custom-file">Icona</label>
		<input type="file" name="icona" id="icona" class="custom-file-input">
		<span class="custom-file-control"></span>
		<br/>
        -->
		<br/>
		<!-- Form per la descrizione breve --> 
		<div id="formAbstr_ita" class="form-group">
        <label for='abstr_ita'>Descrizione breve in Italiano</label>
        <a href="#" data-toggle="tooltip" title="La descrizione breve deve fornire informazioni per incuriosire l'utente ad aprire l'evento. La dimensione consigliata è di 30 parole."><span class="glyphicon glyphicon-info-sign"></span></a>
		<textarea name="abstr_ita" class="form-control textControl" rows="5" id="abstr_ita"><?php if($mess=="errore"){echo $_SESSION['abstr_ita'];}else{ echo $abstr_ita;} ?></textarea>
		<span id="glyphiconAbstrIta"></span>
		<span id="helpAbstrIta" class="help-block">La dimensione massima consigliata è di 30 parole</span>
		<br/>
		</div>
		<div id="formAbstr_eng" class="form-group">
		<label for='abstr_eng'>Brief description in English</label>
        <a href="#" data-toggle="tooltip" title="Brief description have to provide information to excite curiosity. The recommended size is about 30 words."><span class="glyphicon glyphicon-info-sign"></span></a>
		<textarea name="abstr_eng" class="form-control textControl" rows="5" id="abstr_eng"><?php if($mess=="errore"){echo $_SESSION['abstr_eng'];}else{ echo $abstr_eng;} ?></textarea>
		<span id="glyphiconAbstrEng"></span>
		<span id="helpAbstrEng" class="help-block">The maximum recommended size is 30 words</span>
		<br/>
		</div>
		<!-- Form per la descrizione --> 
		<div id="formDesc_ita" class="form-group">
		<label for='desc_ita'>Descrizione in italiano</label>
        <a href="#" data-toggle="tooltip" title="La descrizione lunga deve fornire informazioni corrette e opportunamente documentate. Si possono inserire note usando la funzione apice per il numero ed inserendo la nota in fondo alla descrizione. La dimensione raccomandata è di circa 150 parole."><span class="glyphicon glyphicon-info-sign"></span></a>
		<textarea name="desc_ita" class="form-control longTextControl" rows="10" id="desc_ita"><?php if($mess=="errore"){echo $_SESSION['desc_ita'];}else{ echo $desc_ita;} ?></textarea>
        <span id="helpDescIta" class="help-block">La dimensione massima consigliata è di 150 parole</span>
		<br/>
		</div>
		<div id="formDesc_eng" class="form-group">
		<label for='desc_eng'>Description in English</label>
        <a href="#" data-toggle="tooltip" title="Long descriprion have to provide correct and documented information. You can insert notes with the apex function and inserting the note in the bottom of description. The recomended size is about 150 words."><span class="glyphicon glyphicon-info-sign"></span></a>
		<textarea name="desc_eng" class="form-control longTextControl" rows="10" id="desc_eng"><?php if($mess=="errore"){echo $_SESSION['desc_eng'];}else{ echo $desc_eng;} ?></textarea>
        <span id="helpDescEng" class="help-block">The maximum recommended size is 150 words</span>
		<br/>
		</div>
        <!-- Form per i riferimenti -->
        <div id="formRiferimenti" class="form-group">
		<label for='riferimenti'>Riferimenti</label>
        <a href="#" data-toggle="tooltip" title="Inserire i riferimenti della descrizione lunga. I riferimenti devo essere inseriti nella seguente forma: Autore/i, Titolo Opera, in titolo Rivista, Casa Editrice, Luogo, Anno, Link alla risorsa"><span class="glyphicon glyphicon-info-sign"></span></a>
		<textarea name="riferimenti" class="form-control textControl" rows="5" id="riferimenti"><?php if($mess=="errore"){echo $_SESSION['riferimenti'];}else{ echo $riferimenti;} ?></textarea>
        <span id="helpRiferimenti" class="help-block">Inserire i testi da riferire nella seguente forma: Autore/i, Titolo Opera, in titolo Rivista, Casa Editrice, Luogo, Anno, Link alla risorsa</span>
		<br/>
		</div>
		<!-- Form per le keywords --> 
		<label for="keywords">Keywords</label>
        <a href="#" data-toggle="tooltip" title="Inserire le parole chiave relative all'evento"><span class="glyphicon glyphicon-info-sign"></span></a>
        <input type="text" name="keywords" class="form-control" id="keywords" value="<?php if($mess=="errore"){echo $_SESSION['keywords'];}else{  echo $keywords;} ?>">
		<span class="help-block">Separare le parole con un punto e virgola (;)</span>
		<br/>
        <!-- Redattore -->
        <label for="autore">Autori</label>
		<input type="text" name="autore" class="form-control" id="autore" readonly value="<?php echo $autori; ?>">
		<br/>
             
        <!-- Salvato -->
        <div class='col-lg-2'>
        <label for="salvato">Salvato da:</label>
		<input type="text" name="salvato" class="form-control" id="salvato" readonly value="<?php echo $autore; ?>">
        </div>
		<!-- Stato -->
        <?php if($redattore == 1 && $revisore == 0){
        echo "<div class='col-lg-3'>";
        echo "<label for='Iapprovazione'>I approvazione</label>
		<input type='text' name='Iapprovazione' class='form-control col-lg-2' id='Iapprovazione' readonly value='in attesa'></div>";
        echo "<div class='col-lg-3'>";
        echo "<label for='IIapprovazione'>II approvazione</label>
		<input type='text' name='IIapprovazione' class='form-control col-lg-2' id='IIapprovazione' readonly value='in attesa'></div>";
        echo "<div class='col-lg-4'>";
        echo "<label for='stato'>Stato</label>
		<input type='text' name='stato' class='form-control col-lg-2' id='stato' readonly value='Approvazione 0/2'></div>";
        echo "<br class='stop'/>";
        }else if($redattore == 1 && $revisore == 1){
        echo "<div class='col-lg-3'>";
        echo "<label for='Iapprovazione'>I approvazione</label>
		<input type='text' name='Iapprovazione' class='form-control id='Iapprovazione' readonly value='".$nome_completo."'></div>";
        echo "<div class='col-lg-3'>";
        echo "<label for='IIapprovazione'>II approvazione</label><br/>
		<input type='text' name='IIapprovazione' class='form-control' id='IIapprovazione' readonly value='in attesa'></div>";
        echo "<div class='col-lg-4'>";
        echo "<label for='stato'>Stato</label><br/>
		<input type='text' name='stato' class='form-control' id='stato' readonly value='Approvazione 1/2'></div>";
        echo "<br class='stop'/>";
            }
        ?>
        <br/>
		<div class="pull-right">
        <input type="submit" name="salva" id="salva" class="btn btn-success" value="Salva">
        <button type="submit" name="preview" id="preview" class="btn btn-warning">Preview</button>
		<button type="button" id="applica" class="btn btn-info" data-toggle="modal" data-target="#modalApprovazione">Invia in approvazione</button>
		</div>
        <div id="modalApprovazione" class="modal fade">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Invia l'evento in approvazione</h4>
   </div>
   <div class="modal-body">
    <p class="alert alert-info">Stai inviando l'evento in approvazione. Se vuoi fare altre modifiche clicca su annulla.</p>
    <p class="alert alert-danger" id="campiMancanti"></p>
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Annulla</button>
    <input type="submit" name="invia" id="invia" class="btn btn-info" value="Invia in approvazione">
   </div>
  </div>
 </div>
</div>
            </form>
		</div>
</div>
<div class="HMR_Footer">


    <div class="HMR_FooterTop">
        <img id="HMR_imgFooter" src="../../../Assets/Images/CC_By-Nc-Nd-Eu-80x28.png" alt=''> <div id="HMR_scrittaFooterUp">Copyright © 2017 Nicolò Pratelli - Giovanni A. Cignoni</div> <div id="HMR_scrittaFooterBottom">Pagina creata: 20/12/2012; ultima modifica: 20/20/1234</div>
    </div>

    <div class="HMR_FooterBottom">
        <div id="HMR_contatti"><a>contatti</a></div>
        <div id="HMR_persone"><a>persone</a></div>
        <div id="HMR_login"><a href="amministrazione/asset/html/autenticazione.php">Login</a></div>
    </div>

</div>
</body>
</html>
