<style>
.toggle-btn-grp.cssonly * {
    width:140px;
    height:30px;
    line-height:30px;
}
.toggle-btn-grp.cssonly div {
    display:inline-block;
    position:relative;
    margin:5px 2px;
}

.toggle-btn-grp.cssonly div label {
    position:absolute;
    z-index:0;
    padding:0;
    text-align:center;
}

.toggle-btn-grp.cssonly div input {
    position:absolute;
    z-index:1;
    cursor:pointer;
    opacity:0;
}

.toggle-btn-grp.cssonly div:hover label {
    border:solid 1px #a0d5dc !important; 
    background:#f1fdfe;
}

.toggle-btn-grp.cssonly div input:checked + label {
    background:lightgreen;
    border:solid 1px green !important; 
}
</style>
<?php
//ler o arquivo com dados dos aeroportos, colocando cada linha em um vetor
$nomeArquivoDadosMeteor = 'pen_new.txt'; //trocar este nome de arquivo pelo nome que o script da Meteorologia gera

$arquivoMeteorologico = file($nomeArquivoDadosMeteor, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//iniciando a criação do arquivo de pontos json (coordenadas)
$nomeArquivoJSON = 'pontos.json';
$json = '[' . PHP_EOL;
file_put_contents($nomeArquivoJSON, $json, LOCK_EX);	

//crição da matriz que guarda a ordem de precedência de fenômenos dos ícones - false significa que o fenômeno não tem o referido ícone
$icones[0] = array('CAVOK', './icones/icones-visuais/CAVOK.png', false, false);
$icones[1] = array('SKC', './icones/icones-visuais/CAVOK.png', false, false);
$icones[2] = array('FEW', './icones/icones-visuais/FEW.png', false, false);
$icones[3] = array('SCT', './icones/icones-visuais/SCT.png', false, false);
$icones[4] = array('BKN', './icones/icones-visuais/BKN.png', false, false);
$icones[5] = array('OVC', './icones/icones-visuais/OVC.png', false, false);
$icones[6] = array('DZ', './icones/icones-visuais/DZ.png', './icones/icones-simbolicos/DZ.png', false);
$icones[7] = array('HZ', './icones/icones-visuais/HZ.png', './icones/icones-simbolicos/HZ.png', false);
$icones[8] = array('BR', './icones/icones-visuais/BR.png', './icones/icones-simbolicos/BR.png', false);
$icones[9] = array('+DZ', './icones/icones-visuais/+DZ.png', './icones/icones-simbolicos/+DZ.png', false);
$icones[10] = array('RA', './icones/icones-visuais/RA.png', './icones/icones-simbolicos/RA.png', false);
$icones[11] = array('-RA', './icones/icones-visuais/RA.png', false, false);
$icones[12] = array('+RA', './icones/icones-visuais/RA.png', './icones/icones-simbolicos/+RA.png', './icones/icone-alerta/RA.png');
$icones[13] = array('TS', './icones/icones-visuais/TS.png', './icones/icones-simbolicos/TS.png', './icones/icone-alerta/TS.png');
$icones[14] = array('CB', './icones/icones-visuais/CB.png', './icones/icones-simbolicos/CB.png',false);
$icones[15] = array('TCU', './icones/icones-visuais/TCU.png', './icones/icones-simbolicos/TCU.png', false);
$icones[16] = array('GR', './icones/icones-visuais/GR.png', './icones/icones-simbolicos/GR.png', './icones/icone-alerta/GR.png');
$icones[17] = array('GS', './icones/icones-visuais/GR.png', false, false);
$icones[18] = array('SQ', './icones/icones-visuais/SQ.png', false, false);
$icones[19] = array('FG', './icones/icones-visuais/FG.png', './icones/icones-simbolicos/FG.png', './icones/icone-alerta/FG.png');
$icones[20] = array('FC', './icones/icones-visuais/FG.png', false, false);
$icones[21] = array('VA', './icones/icones-visuais/VA.png', './icones/icones-simbolicos/VA.png', false);
$icones[22] = array('PL', './icones/icones-visuais/PL.png', './icones/icones-simbolicos/PL.png', false);
$icones[23] = array('FU', './icones/icones-visuais/FU.png', './icones/icones-simbolicos/FU.png', false);
$icones[24] = array('SN', './icones/icones-visuais/SN.png', './icones/icones-simbolicos/SN.png', false);
$icones[25] = array('VCTS', './icones/icones-visuais/VCTS.png', false, false);
$icones[26] = array('SHRA', './icones/icones-visuais/SHRA.png', './icones/icones-simbolicos/SHRA.png', false);
$icones[27] = array('SHGR', false, './icones/icones-simbolicos/SHGR.png', false);
$icones[28] = array('SHTS', './icones/icones-visuais/SHTS.png', false, false);
$icones[29] = array('TSSN', './icones/icones-visuais/TSSN.png', false, false);
$icones[30] = array('TSPL', './icones/icones-visuais/TSPL.png', './icones/icones-simbolicos/TSPL.png', false);
$icones[31] = array('TSGR', './icones/icones-visuais/TSGR.png', './icones/icones-simbolicos/TSGR.png', false);
$icones[32] = array('TSRA', './icones/icones-visuais/TSRA.png', './icones/icones-simbolicos/TSRA.png', false);
$icones[33] = array('TSGS', './icones/icones-visuais/TSGS.png', false, false);


//lendo cada linha do arquivo de dados meteorológicos 

foreach($arquivoMeteorologico as $indice  => $dados)
	{
	//encontrando todos o códigos de fenômeno em cada linha do vetor arquivo, que estão presente dentro da matriz de ícones
	$ocorrencias = array();
	
	
	for($i=0; $i < count($icones); $i++)
		{
		$vetorInterno = $icones[$i];
		$encontrou = strpos($dados, $vetorInterno[0]);
		if($encontrou) 
			{
			//encontrou, pelo menos, uma ocorrência do fenômeno na matriz de ícones
			$ocorrencias[] = $i;
			}		
		}
					
		//processar as ocorrências de todos os fenômenos, antes de ir para a próxima linha do arquivo de dados meteorológicos
		@$fenomenoMaisImportante = max($ocorrencias);
		
		//definindo, via parâmetro da URL, se o ícone a ser mostrado será visual, simbólico ou de alerta
		if(isset($_GET["tipo"]))	
		 $tipo = $_GET['tipo'];
		else
			$tipo = "visual";
		
		if($tipo == 'visual')
			//ícone visual, para a página inicial
			$imagem = $icones[$fenomenoMaisImportante][1];
		elseif($tipo == 'simbolico')
			//ícone simbólico, se existir (não false)
			$imagem = $icones[$fenomenoMaisImportante][2] or '';
		elseif($tipo == 'alerta')
			//ícone de alerta, se existir
			$imagem = $icones[$fenomenoMaisImportante][3] or '';
			
			//continuando a criação do arquivo json, inserindo as coordenadas do aeroporto, bem como o ícone associado ao fenômeno mais importante naquele aeroporto e a linha de informação do arquivo meteorológico
			$vetorTemporario = explode(' ', $dados);
			$latitude = $vetorTemporario[0];
			$longitude = $vetorTemporario[1];
			$infotxt = "Latitude: $latitude </BR> Longitude: $longitude ";
			if($imagem) //cria o ponto somente se houver icone associado
				//{$json = "{\"Latitude\": $latitude, \"Longitude\": $longitude, \"icone\": \"$imagem\", \"Informacao\": \"$infotxt\"}," . PHP_EOL;
				//file_put_contents($nomeArquivoJSON, $json, FILE_APPEND | LOCK_EX);
				//}
				{$json = "{\"Latitude\": $latitude, \"Longitude\": $longitude, \"icone\": \"$imagem\", \"Informacao\": \"$dados\"}," . PHP_EOL;
				file_put_contents($nomeArquivoJSON, $json, FILE_APPEND | LOCK_EX);
				}


	} //fechar o laço
				
			//fechando o json
				$json = ']';
				file_put_contents($nomeArquivoJSON, $json, FILE_APPEND | LOCK_EX);
				
			//tratando a última linha do json para tirar a vírgula ao final do último objeto
			$json = file_get_contents($nomeArquivoJSON);
			$posicaoDaVirgula = strrpos($json, ',');
			if($posicaoDaVirgula)
				$json[$posicaoDaVirgula] = ' ';
			file_put_contents($nomeArquivoJSON, $json, LOCK_EX);
																					
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
 <meta charset="utf-8" />
 <title> Google Maps API v3: Criando um mapa personalizado </title>
	<link rel="stylesheet" href="mapa.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>	
</head>
 
<body>

<br>

<div class="toggle-btn-grp cssonly">
    <div><a href="modelo.php?tipo=visual" title="Ìcone Visual"><input type="radio" name="group4"/><label onclick="modelo.php?tipo=visual" class="toggle-btn">Ícone Visual</label></a></div>
    <div><a href="modelo.php?tipo=simbolico" title="Ìcone Simbólico"><input type="radio" name="group4"/><label onclick="modelo.php?tipo=simbolico" class="toggle-btn">Ícone Simbólico</label></a></div>
    <div><a href="modelo.php?tipo=alerta" title="Ìcone de Alerta"><input type="radio" name="group4"/><label onclick="modelo.php?tipo=alerta" class="toggle-btn">Alerta</label></a></div>
</div>
<br> <br>

	<div id="mapa"></div>
	
	<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyD34TmxQuA2QEG8YYI3EG90BM9vgvP0qBw&amp;sensor=false"></script>

	<script src="mapa.js"></script>

</body>

</html>
