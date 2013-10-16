<?php 
	require_once 'request.php';

	if(isset($_POST['pag'])){
		$pag=$_POST['pag'];
	}else{
		$pag = 1;
	}

	if(isset($_POST['busca'])){
		$busca=$_POST['busca'];
	}else{
		$busca = '';
	}

	$res = XRI::request('http://api.iobconcursos.com',array("pag"=>$pag,"busca"=>$busca));
	#echo $res.'<br/><br/><br/><br/>';
	$xml = simplexml_load_string($res);
?>

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