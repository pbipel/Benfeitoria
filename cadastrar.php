<?php
ini_set('default_charset','UTF-8');
$ponte="";
include("conexao.php");
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
if(isset($_POST['submit'])){
	if((isset($_POST["titulo"]) && $_POST["titulo"]!="") && (isset($_POST["autor"]) && $_POST["autor"]!="") && (isset($_POST["sinopse"]) && $_POST["sinopse"]!="") && (isset($_FILES["imagem"]) && $_FILES["imagem"]["name"]!="") && (isset($_POST["texto"]) && $_POST["texto"]!="")){
		$titulo=utf8_decode($_POST['titulo']);
		$sinopse=utf8_decode($_POST['sinopse']);
		$autor=utf8_decode($_POST['autor']);
		$texto=utf8_decode($_POST['texto']);
		$data=date('Y-m-d');
		$codt="select max(id_post) from posts";
		$codart=mysqli_query ($conexao, $codt);
		$codar=mysqli_fetch_row($codart);
		$coda=$codar[0];
		$id=$coda +1;
		if ($_FILES["imagem"]["size"] > 0){
			$img = $_FILES['imagem']['name'];
			$_UP['extensoes'] = array('png', 'jpg', 'gif', 'jpeg', 'svg', 'bmp');
			$novo_array=explode('.', $_FILES['imagem']['name']);
			$extensao = strtolower(end($novo_array));
			if (array_search($extensao, $_UP['extensoes']) === null){
				$ponte="asucesso('e');";
			}else{
				$nome_temporario=$_FILES["imagem"]["tmp_name"];
				$nome_real=$_FILES["imagem"]["name"];
				$_UP['pasta'] = "Uploads/";
				$dh = opendir($_UP['pasta']);
				$imgs=0;
				while ($filename = readdir($dh)) {
					if(preg_match('/'.$id.'_[0-9]_'.$nome_real.'/', $filename)){
						$imgs++;
					}
				}
				$local_imagem = $_UP['pasta'].$id.'_'.$imgs.'_'.$nome_real;
				utf8_encode($local_imagem);
				move_uploaded_file($nome_temporario, $local_imagem);
				$inserir="insert into posts set id_post='$id',titulo='$titulo',sinopse='$sinopse',autor='$autor',imagem='$local_imagem',texto='$texto',data='$data',visto='0'";
				$result = mysqli_query($conexao, $inserir);
				if(!$result){
					$ponte="asucesso('a');";
				}else{
					$ponte="sucesso('a');";
				}
			}
		}else{
			$ponte="asucesso('t');";
		}
	}else{
		$ponte="asucesso('v');";
	}
}
?>
<html lang="pt-br">
	<head>
		<title>Nova Postagem</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, user-scalable=no" />
		<link href="Imagens/b.png" rel="shortcut icon" type="image/x-icon"/>
		<link rel="stylesheet" href="Estilos/css.css" type="text/css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
	</head>
	<script type="text/javascript">
		$(function(){ 
			var div = $('#div-menu-title');  
			$("#navegation").scroll(function () { 
				if ($(this).scrollTop() > 15) { 
					div.addClass("desce-menu"); 
				} else { 
					div.removeClass("desce-menu"); 
				} 
			});  
			var inicio = $('#nav');    
			$(window).scroll(function () { 
				if ($(this).scrollTop() > 30) { 
					inicio.addClass("desce"); 
				} else { 
					inicio.removeClass("desce");
				} 
			});  
		}); 
		function dropdownChecked(x){
			if(x.checked==true){
				document.getElementById("dropdown-span").className="seta-cima";
			}else{
				document.getElementById("dropdown-span").className="seta-baixo";
			}
		}
		function preview(input) {
			extensoes_permitidas = new Array(".gif", ".jpg", ".png", ".jpeg", ".svg", ".bmp");
			extensao = (input.value.substring(input.value.lastIndexOf("."))).toLowerCase();
			if (extensoes_permitidas.indexOf(extensao)==-1 || input.files[0].size==0) {
				document.getElementById('preview_image').src="";
				document.getElementById('texto-imagem').innerText="Clique aqui para selecionar uma foto";
				input.value=null;
			}else{
				document.getElementById('texto-imagem').innerText="Clique aqui para trocar a foto";
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#preview_image')
					.attr('src', e.target.result)				
				};
				reader.readAsDataURL(input.files[0]);
			}
		}
		function pri_mai(obj){
			str = obj.value;
			qtd = obj.value.length;
			prim = str.substring(0,1);
			resto = str.substring(1,qtd);
			str = prim.toUpperCase() + resto;
			obj.value = str;
		}
		function asucesso(x){
			if (x=="a"){
				document.getElementById('resposta').innerHTML="<div class=\"respos\">Postagem não enviada com sucesso.</div>";
			}else if (x=="v"){
				document.getElementById('resposta').innerHTML="<div class=\"respos\">Campo vazio detectado.</div>";
			}else if (x=="e"){
				document.getElementById('resposta').innerHTML="<div class=\"respos\">É permitido somente imagens com as seguintes extensões: png, jpg, svg, bmp ou gif.</div>";
			}else if (x=="t"){
				document.getElementById('resposta').innerHTML="<div class=\"respos\">Imagem selecionada sem tamanho.</div>";
			}
			document.getElementById('resposta').setAttribute("class", "resposta");
		}
		function sucesso(x){
			if (x=="a"){
				document.getElementById('resposta').innerHTML="<div class=\"respos respostapos\">Postagem enviada com sucesso.</div>";
			}
			document.getElementById('resposta').setAttribute("class", "resposta");
		}
	</script>	
	<body onload="<?php echo "$ponte";?>">
		<div id="nav" class="nav"></div>
		<header class="pag">
			<label for="control-nav" class="logo-texto">Benfeitoria</label>
			<input type="checkbox" id="control-nav" />
			<label for="control-nav" class="control-nav"></label>
			<label for="control-nav" class="control-nav-close"></label>
			<div id="div-menu-title" class="div-menu-title"></div>
			<nav id="navegation">
				<ul class="ul-navegation">
					<li>
						<a href="index.php">
							<label>P&aacute;gina Inicial</label>
						</a>
					</li>
					<li class="marca-pag-atual">
						<a href="cadastrar.php">
							<label>Nova postagem</label>
						</a>
					</li>
					<li>
						<a href="postagem.php?categoria=titulo">
							<label>T&iacute;tulos</label>
						</a>
					</li>
					<li>
						<a href="postagem.php?categoria=autor">
							<label>Autores</label>
						</a>
					</li>
					<li>
						<a href="postagem.php?categoria=sinopse">
							<label>Sinopses</label>
						</a>
					</li>
					<li>
						<a href="postagem.php?categoria=data">
							<label>Datas</label>
						</a>
					</li>
				</ul>
			</nav>
		</header>
		<div>
			<input type="checkbox" class="invisible" onchange="dropdownChecked(this);" id="dropdown-checkbox" />
			<div class="dropdown">
				<label class="dropdown-title" for="dropdown-checkbox">Mais vistas<span id="dropdown-span" class="seta-baixo"></span></label>
			</div>
			<nav class="dropdown-content">
				<ul class="ul-dropdown">
					<?php
						$vistos="select * from posts order by visto desc limit 5";
						$visto=mysqli_query ($conexao, $vistos);
						$cont_visto=mysqli_num_rows ($visto);
						if($cont_visto==0){
							echo "
								<li class=\"back-color-drop\">
									<a class=\"cursor-default\">
									<label class=\"cursor-default\">Nenhum post encontrado</label></a>
								</li>
							";
						}else{
							while($consulta=mysqli_fetch_array($visto)){
								echo "
									<li>
										<a href=\"postagem.php?id=".utf8_encode($consulta['id_post'])."\">
										<label>".utf8_encode($consulta['titulo'])."</label></a>
									</li>
								";
							}
						}
					?>
				</ul>
			</nav>
		</div>
		<div class="content">
			<div id="resposta"></div>
			<div class="estrutura-post">
				<form name="id" method="post" action="cadastrar.php" enctype="multipart/form-data">
					<input type="text" class="postagem-titulo input-titulo" name="titulo" onBlur="pri_mai(this);" maxlength="100" placeholder="Digite aqui um t&iacute;tulo" required/>
					<div class="center">
						<span class="post-extra">Por</span>
						<input type="text" class="input-autor" name="autor" onBlur="pri_mai(this);" maxlength="50" placeholder="Digite aqui o autor" required/>
						<span class="post-extra">|</span>
						<label class="post-extra">Em <?php echo strftime('%d de %B de %Y', strtotime('today'));?></label>
					</div>
					<input type="text" maxlength="150" name="sinopse" onBlur="pri_mai(this);" class="postagem-sinopse input-sinopse" placeholder="Digite aqui uma pequena pr&eacute;via" required/>
					<label for="file-input">
						<div class="div-image">
							<img class="postagem-imagem" id="preview_image"/>
							<label for="file-input" id="texto-imagem" class="photo-text-config">Clique aqui para selecionar uma foto</label>
							<input type="file" name="imagem" id="file-input" accept="image/*" class="input-image" onchange="preview(this);" required/>
						</div>
					</label>
					<textarea name="texto" wrap="hard" class="postagem-texto input-texto" placeholder="Digite aqui o seu texto" onBlur="pri_mai(this);" required></textarea>
					<input type="submit" name="submit" class="center confirma" value="Postar">				
				</form>
			</div>
		</div>
	</body>
</html>