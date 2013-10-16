<?php 
	require_once 'request.php';

	$res = XRI::request('http://api.iobconcursos.com',array("pag"=>1));
	#echo $res.'<br/><br/><br/><br/>';
	$xml = simplexml_load_string($res);

?>

<html>
<head>
	<title>RENDER</title>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
	<link rel="stylesheet" type="text/css" href="gumby/css/gumby.css"/>
</head>
<body>
	<div class="row">
		<h3 id="titulo">IOB Concursos</h3>
		<div class="append field">
			<input type="busca" id="busca" placeholder="O que procura..." class="wide email input">
			<div id="btn-busca" class="medium primary btn"><a href="#">Buscar</a></div>
        </div>
		<table class="striped rounded" id="tabela">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">Título</th>
					<th class="text-center">Remuneração</th>
					<th class="text-center">Vagas</th>
					<th class="text-center">Carga Horária</th>
					<th class="text-center">Data da Prova</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($xml as $x){ ?>
					<tr>
						<td class="text-center"><?php echo $x->ID_curso; ?></td>
						<td><a href="<?php echo $x->url_compra; ?>" target="_blank"><?php echo $x->titulo_curso; ?></a></td>
						<td class="text-center"><?php echo (empty($x->remuneracao) ? '-' : 'R$ '.$x->remuneracao) ?></td>
						<td class="text-center"><?php echo (empty($x->vagas) ? '-' : $x->vagas ) ?></td>
						<td class="text-center"><?php echo (empty($x->carga_horaria) ? '-' : $x->carga_horaria ) ?></td>
						<td class="text-center"><?php echo (empty($x->data_prova) ? '-' : date('d/m/Y',strtotime($x->data_prova)) ) ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="one pull_right">
			<div class="medium btn pill-left default"><a id="back" data-pag='1' href="#">&laquo;</a></div>
			<div class="medium btn pill-right default"><a id="next" data-pag='2' href="#">&raquo;</a></div>
		</div>
	</div>
	<script type="text/javascript" src="gumby/js/libs/jquery-2.0.2.min.js"></script>
	<script type="text/javascript" src="gumby/js/libs/gumby.js"></script>
	<script type="text/javascript" src="gumby/js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){

			var busca = '';
			var old_busca = '';

			$('#next').on('click',function(){
				$('#titulo').text('Carregando...');
				var link = $(this);
				var request = $.ajax({
					url: "get_cursos.php",
					type: "POST",
					data: {pag : link.attr('data-pag'),busca:busca}
				});
				request.done(function(msg) {
					console.log(busca);
					$('#titulo').text('IOB Concursos');
					$('#back').attr('data-pag',link.attr('data-pag'))
					$('#next').attr('data-pag',parseInt(link.attr('data-pag'))+1)
					$('#tabela tbody').html(msg);
				});
				request.fail(function(jqXHR, textStatus) {
					// MENSAGEM DE ERRO
				});
				return false;
			});

			$('#back').on('click',function(){
				$('#titulo').text('Carregando...');
				var link = $(this);
				var request = $.ajax({
					url: "get_cursos.php",
					type: "POST",
					data: {pag : link.attr('data-pag'),busca:busca}
				});
				request.done(function(msg) {
					console.log(busca);
					$('#titulo').text('IOB Concursos');
					if( parseInt(link.attr('data-pag')) > 0){
						$('#next').attr('data-pag',link.attr('data-pag'));
						$('#back').attr('data-pag',parseInt(link.attr('data-pag'))-1)
					}else{
						$('#next').attr('data-pag','2');
						$('#back').attr('data-pag','1')
					}
					$('#tabela tbody').html(msg);
				});
				request.fail(function(jqXHR, textStatus) {
					// MENSAGEM DE ERRO
				});
				return false;
			});

			$('#btn-busca').on('click',function(){
				busca = $('#busca').val();

				if(busca == ''){
					paginacao = 1;
					$('#next').attr('data-pag','2');
					$('#back').attr('data-pag','1');
				}else{
					if(old_busca != busca){
						paginacao = 1;
						$('#next').attr('data-pag','2');
						$('#back').attr('data-pag','1');
					}else{
						paginacao = $('#next').attr('data-pag');
					}
				}

				$('#titulo').text('Carregando...');
				var link = $(this);
				var request = $.ajax({
					url: "get_cursos.php",
					type: "POST",
					data: {pag : paginacao,busca:busca}
				});
				request.done(function(msg) {
					console.log(busca);
					$('#titulo').text('IOB Concursos');
					if( parseInt(link.attr('data-pag')) > 0){
						$('#next').attr('data-pag',link.attr('data-pag'));
						$('#back').attr('data-pag',parseInt(link.attr('data-pag'))-1)
					}else{
						$('#next').attr('data-pag','2');
						$('#back').attr('data-pag','1')
					}
					$('#tabela tbody').html(msg);
					old_busca = busca;
				});
				request.fail(function(jqXHR, textStatus) {
					// MENSAGEM DE ERRO
				});
			});

			$("#busca").keypress(function(event) {
				if ( event.which == 13 ) {
					$('#btn-busca').click();
				}
			});
		});
	</script>
</body>
</html>