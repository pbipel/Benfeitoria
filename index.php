<?php
ini_set('default_charset','UTF-8');
$ponte="";
include("conexao.php");
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');
if(isset($_GET['conexao']) && $_GET['conexao']!=""){
	if($_GET['conexao']==0){
		$ponte="sucesso('a');";
	}
}
?>
<html lang="pt-br">
	<head>
		<title>P&aacute;gina Inicial</title>
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
		function sucesso(x){
			if (x=="a"){
				document.getElementById('resposta').innerHTML="<div class=\"respos respostapos\">Post removido com sucesso.</div>";
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
					<li class="marca-pag-atual">
						<a href="index.php">
							<label>P&aacute;gina Inicial</label>
						</a>
					</li>
					<li>
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
		<div class="content center">
		<div id="resposta"></div>
			<?php
				$posts="select * from posts order by data desc";
				$post=mysqli_query ($conexao, $posts);
				$cont_post=mysqli_num_rows ($post);
				if($cont_post==0){
					echo "
						<a href=\"cadastrar.php\">
							<div class=\"post-board\">
								<label class=\"post-titulo\">Nenhum post foi encontrado</label>
								<label class=\"post-extra\">Clique aqui para fazer um post</label>
							</div>
						</a>
					";
				}else{
					while($pos=mysqli_fetch_array($post)){
						echo "
							<a href=\"postagem.php?id=".utf8_encode($pos['id_post'])."\">
								<div class=\"post-board\">
									<label class=\"post-titulo\">".utf8_encode($pos['titulo'])."</label>
									<label class=\"post-extra\">Por ".utf8_encode($pos['autor'])."</label>
									<span class=\"post-extra\">|</span>
									<label class=\"post-extra\">Em ".strftime('%d de %B de %Y', strtotime($pos['data']))."</label>
									<img class=\"post-imagem\" src=\"".utf8_encode($pos['imagem'])."\">
									<p class=\"post-texto\">".utf8_encode($pos['sinopse'])."</p>
								</div>
							</a>
						";
					}
				}
			?>
		</div>	
	</body>
</html>