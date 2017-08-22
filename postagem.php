<?php
ini_set('default_charset','UTF-8');
$ponte="";
include("conexao.php");
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
if(isset($_GET['categoria'])){
	if($_GET['categoria']=="titulo"){
		$title="Títulos";
	}else if($_GET['categoria']=="autor"){
		$title="Autores";
	}else if($_GET['categoria']=="sinopse"){
		$title="Sinopses";
	}else if($_GET['categoria']=="data"){
		$title="Datas";
	}
}else if(isset($_GET['id']) && $_GET['id']!=""){
	$idpost="select * from posts where id_post='$_GET[id]'";
	$idpos=mysqli_query ($conexao, $idpost);
	$idpo=mysqli_fetch_array ($idpos);
	$title=utf8_encode($idpo['titulo']);
	$visto=utf8_encode($idpo['visto']);
	$visto++;
	$inserir="UPDATE posts set visto='$visto' where id_post='$_GET[id]'";
	$result = mysqli_query($conexao, $inserir);
}else if(isset($_POST['remover']) && $_POST['remover']!=""){
	$inserir="delete from posts where id_post='$_POST[remover]'";
	$result = mysqli_query($conexao, $inserir);
	return header('location: index.php?conexao=0');
}
else{
	return header('location: index.php');
}
?>
<html lang="pt-br">
	<head>
		<title><?php echo $title;?></title>
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
	</script>	
	<body>
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
					<li>
						<a href="cadastrar.php">
							<label>Nova postagem</label>
						</a>
					</li>
					<li <?php if($title=="Títulos"){echo "class=\"marca-pag-atual\"";}?>>
						<a href="postagem.php?categoria=titulo">
							<label>T&iacute;tulos</label>
						</a>
					</li>
					<li <?php if($title=="Autores"){echo "class=\"marca-pag-atual\"";}?>>
						<a href="postagem.php?categoria=autor">
							<label>Autores</label>
						</a>
					</li>
					<li <?php if($title=="Sinopses"){echo "class=\"marca-pag-atual\"";}?>>
						<a href="postagem.php?categoria=sinopse">
							<label>Sinopses</label>
						</a>
					</li>
					<li <?php if($title=="Datas"){echo "class=\"marca-pag-atual\"";}?>>
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
										<label class=\"cursor-default\">Nenhum post encontrado</label>
									</a>
								</li>";
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
		<?php
			if(isset($_GET['categoria']) && ($_GET['categoria']=="titulo" || $_GET['categoria']=="autor" || $_GET['categoria']=="sinopse" || $_GET['categoria']=="data")){
				$categoria="select * from posts order by $_GET[categoria] asc";
				$categori=mysqli_query ($conexao, $categoria);
				$cont_categoria=mysqli_num_rows ($categori);
				echo "<div class=\"content center\">";
				if($cont_categoria==0){
					echo "
						<a href=\"cadastrar.php\">
							<div class=\"post-board\">
								<label class=\"post-titulo\">Nenhum post foi encontrado</label>
								<label class=\"post-extra\">Clique aqui para fazer um post</label>
							</div>
						</a>
					";
				}else{
					while($categor=mysqli_fetch_array($categori)){
						if($_GET['categoria']=="data"){
							$titulo=strftime('%d de %B de %Y', strtotime($categor['data']));
						}else{
							$titulo=utf8_encode($categor[$_GET['categoria']]);
						}
						echo "
							<a href=\"postagem.php?id=".utf8_encode($categor['id_post'])."\">
								<div class=\"post-board\">
									<label class=\"post-titulo\">".$titulo."</label>
								</div>
							</a>
						";
					}
				}
				echo "</div>";
			}
			if(isset($_GET['id']) && $_GET['id']!=""){
				$idpost="select * from posts where id_post='$_GET[id]'";
				$idpos=mysqli_query ($conexao, $idpost);
				$cont_idpos=mysqli_num_rows ($idpos);
				echo "<div class=\"content\">";
				if($cont_idpos==0){
					echo "
						<a href=\"cadastrar.php\">
							<div class=\"post-board\">
								<label class=\"post-titulo\">Nenhum post foi encontrado</label>
								<label class=\"post-extra\">Clique aqui para fazer um post</label>
							</div>
						</a>
					";
				}else{
					while($idpo=mysqli_fetch_array ($idpos)){
						echo "
							<div class=\"estrutura-post\">
								<form name=\"remove\" method=\"post\" action=\"postagem.php\">
									<input type=\"hidden\" name=\"remover\" value=".utf8_encode($idpo['id_post']).">
									<label class=\"postagem-titulo\">".utf8_encode($idpo['titulo'])."</label>
									<div class=\"center\">
										<label class=\"post-extra\">Por ".utf8_encode($idpo['autor'])."</label>
										<span class=\"post-extra\">|</span>
										<label class=\"post-extra\">Em ".strftime('%d de %B de %Y', strtotime($idpo['data']))."</label>
									</div>
									<label class=\"postagem-sinopse\">".utf8_encode($idpo['sinopse'])."</label>
									<div class=\"div-postagem-image\">
										<img class=\"postagem-imagem\" src='".utf8_encode($idpo['imagem'])."'/>
									</div>
									<article class=\"postagem-texto\">".nl2br(utf8_encode($idpo['texto']))."</article>
									<input type=\"submit\" name=\"submit\" class=\"center deleta\" value=\"Remover\">
								</form>
							</div>
						";
					}
				}
				echo "</div>";
			}
		?>
	</body>
</html>