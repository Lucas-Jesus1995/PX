<?php
function habilitarErro()
{
	@ini_set('display_errors', '1');
	error_reporting(E_ALL);
}

date_default_timezone_set('America/Sao_Paulo');

function autenticaloginusuario($login, $senha)
{
	$sql = "SELECT * FROM users	WHERE login = '$login' LIMIT 0,1";
	$con = bancoMysqli();
	$query = mysqli_query($con,$sql);
	//query que seleciona os campos que voltarão para na matriz
	if($query)
	{
		//verifica erro no banco de dados
		if(mysqli_num_rows($query) > 0)
		{
			// verifica se retorna usuário válido
			$user = mysqli_fetch_array($query);
			if($user['password'] == $_POST['senha'])// compara as senhas
			{
				$data = date('Y-m-d H:i:s');
				$sql_date = "UPDATE `users` SET `dateLastAccess` = '$data' WHERE `users`.`login` = '$login'";
				$query_date = mysqli_query($con,$sql_date);
				session_start();
				$_SESSION['login'] = $user['login'];
				$_SESSION['nome'] = $user['local'];
				$_SESSION['idUser'] = $user['id'];
				header("Location: visual/index_usuario.php");
			}
			else
			{
				return "<div class='alert alert-danger text-left' role='alert'>Senha ou Login, incorretos!</div>";
			}
		}
		else
		{
			return "<div class='alert alert-danger text-left' role='alert'>Usuário não existe!</div>";
		}
	}
	else
	{
		return "<div class='alert alert-danger text-left' role='alert'>Erro no Banco de Dados</div>";
	}
}


function autenticaloginadministrador($login, $senha)
{
	$sql = "SELECT * FROM administrators WHERE login = '$login' LIMIT 0,1";
	$con = bancoMysqli();
	$query = mysqli_query($con,$sql);
	//query que seleciona os campos que voltarão para na matriz
	if($query)
	{
		//verifica erro no banco de dados
		if(mysqli_num_rows($query) > 0)
		{
			// verifica se retorna usuário válido
			$admin = mysqli_fetch_array($query);
			if($admin['password'] == $_POST['senha'])
			{
				// compara as senhas
				session_start();
				$_SESSION['login'] = $admin['login'];
				$_SESSION['nome'] = $admin['local'];
				$_SESSION['idAdm'] = $admin['id'];
				header("Location: visual/index_administrador.php");
			}
			else
			{
				return "<div class='alert alert-danger' role='alert'>Senha ou Login, incorretos!</div>";
			}
		}
		else
		{
			return "<div class='alert alert-danger' role='alert'>Usuário não existe!</div>";
		}
	}
	else
	{
		return "<div class='alert alert-danger' role='alert'>Erro no banco de dados!</div>";
	}
}


//saudacao inicial
function saudacao()
{
	$hora = date('H');
	if(($hora >= 12) AND ($hora <= 18))
	{
		return "<img src='images/sun.png'>&nbsp;&nbsp;Boa tarde";
	}
	else if(($hora > 18) AND ($hora <= 23))
	{
		return "<img src='images/moon.png'>&nbsp;&nbsp;Boa noite";
	}
	else if(($hora >= 0) AND ($hora <= 4))
	{
		return "<img src='images/moon.png'>&nbsp;&nbsp;Boa noite";
	}
	else if(($hora > 4) AND ($hora <=12))
	{
		return "<img src='images/sunrise.png'>&nbsp;&nbsp;Bom dia";
	}
}
// Formatação de datas, valores
// Retira acentos das strings
function semAcento($string)
{
	$newstring = preg_replace("/[^a-zA-Z0-9_.]/", "", strtr($string, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ ", "aaaaeeiooouucAAAAEEIOOOUUC_"));
	return $newstring;
}
//retorna data d/m/y de mysql/date(a-m-d)
function exibirDataBr($data)
{
	if($data == '0000-00-00' || $data == NULL)
	{
		return "00-00-0000";
	}
	else
	{
		$timestamp = strtotime($data);
		return date('d/m/Y', $timestamp);
	}
}
// retorna datatime sem hora
function retornaDataSemHora($data)
{
	$semhora = substr($data, 0, 10);
	return $semhora;
}
//retorna data d/m/y de mysql/datetime(a-m-d H:i:s)
function exibirDataHoraBr($data)
{
	if($data == '0000-00-00 00:00:00')
	{
		return "0000-00-00 00:00:00";
	}
	else
	{
		$timestamp = strtotime($data);
		return date('d/m/y - H:i:s', $timestamp);
	}
}
//retorna hora H:i de um datetime
function exibirHora($data)
{
	$timestamp = strtotime($data);
	return date('H:i', $timestamp);
}
//retorna data mysql/date (a-m-d) de data/br (d/m/a)
function exibirDataMysql($data)
{
	list ($dia, $mes, $ano) = explode ('/', $data);
	$data_mysql = $ano.'-'.$mes.'-'.$dia;
	return $data_mysql;
}
//retorna o endereço da página atual
function urlAtual()
{
	$dominio= $_SERVER['HTTP_HOST'];
	$url = "http://" . $dominio. $_SERVER['REQUEST_URI'];
	return $url;
}
//retorna valor xxx,xx para xxx.xx
function dinheiroDeBr($valor)
{
	$valor = str_ireplace(".","",$valor);
	$valor = str_ireplace(",",".",$valor);
	return $valor;
}
//retorna valor xxx.xx para xxx,xx
function dinheiroParaBr($valor)
{
	$valor = number_format($valor, 2, ',', '.');
	return $valor;
}
//use em problemas de codificacao utf-8
function _utf8_decode($string)
{
	$tmp = $string;
	$count = 0;
	while (mb_detect_encoding($tmp)=="UTF-8")
	{
		$tmp = utf8_decode($tmp);
		$count++;
	}
	for ($i = 0; $i < $count-1 ; $i++)
	{
		$string = utf8_decode($string);
	}
	return $string;
}
//retorna o dia da semana segundo um date(a-m-d)
function diasemana($data)
{
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);
	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
	switch($diasemana)
	{
		case"0": $diasemana = "Domingo";       break;
		case"1": $diasemana = "Segunda-Feira"; break;
		case"2": $diasemana = "Terça-Feira";   break;
		case"3": $diasemana = "Quarta-Feira";  break;
		case"4": $diasemana = "Quinta-Feira";  break;
		case"5": $diasemana = "Sexta-Feira";   break;
		case"6": $diasemana = "Sábado";        break;
	}
	return "$diasemana";
}

//soma(+) ou substrai(-) dias de um date(a-m-d)
function somarDatas($data,$dias)
{
	$data_final = date('Y-m-d', strtotime("$dias days",strtotime($data)));
	return $data_final;
}

//retorna a diferença de dias entre duas datas
function diferencaDatas($data_inicial,$data_final)
{
	// Define os valores a serem usados
	// Usa a função strtotime() e pega o timestamp das duas datas:
	$time_inicial = strtotime($data_inicial);
	$time_final = strtotime($data_final);
	// Calcula a diferença de segundos entre as duas datas:
	$diferenca = $time_final - $time_inicial; // 19522800 segundos
	// Calcula a diferença de dias
	$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
	return $dias;
}

function gravarLog($log)
{
	//grava na tabela log os inserts e updates
	$logTratado = addslashes($log);
	$idUser = $_SESSION['idUser'];
	$ip = $_SERVER["REMOTE_ADDR"];
	$data = date('Y-m-d H:i:s');
	$sql = "INSERT INTO `log` (`id`, `idUsuario`, `enderecoIP`, `dataLog`, `descricao`)
		VALUES (NULL, '$idUser', '$ip', '$data', '$logTratado')";
	$mysqli = bancoMysqli();
	$mysqli->query($sql);
}

function geraOpcao($tabela,$select)
{
	//gera os options de um select
	$sql = "SELECT * FROM $tabela ORDER BY 2";

	$con = bancoMysqli();
	$query = mysqli_query($con,$sql);
	while($option = mysqli_fetch_row($query))
	{
		if($option[0] == $select)
		{
			echo "<option value='".$option[0]."' selected >".$option[1]."</option>";
		}
		else
		{
			echo "<option value='".$option[0]."'>".$option[1]."</option>";
		}
	}
}

function geraOpcaoPublicado($tabela,$select)
{
	//gera os options de um select
	$sql = "SELECT * FROM $tabela WHERE published = 1 ORDER BY 2";

	$con = bancoMysqli();
	$query = mysqli_query($con,$sql);
	while($option = mysqli_fetch_row($query))
	{
		if($option[0] == $select)
		{
			echo "<option value='".$option[0]."' selected >".$option[1]."</option>";
		}
		else
		{
			echo "<option value='".$option[0]."'>".$option[1]."</option>";
		}
	}
}

function geraCombobox($tabela,$campo,$order,$select)
{
	//gera os options de um select
	$sql = "SELECT * FROM $tabela ORDER BY $order";

	$con = bancoMysqli();
	$query = mysqli_query($con,$sql);
	while($option = mysqli_fetch_row($query))
	{
		if($option[0] == $select)
		{
			echo "<option value='".$option[0]."' selected >".$option[$campo]."</option>";
		}
		else
		{
			echo "<option value='".$option[0]."'>".$option[$campo]."</option>";
		}
	}
}

function recuperaDados($tabela,$campo,$variavelCampo)
{
	//retorna uma array com os dados de qualquer tabela. serve apenas para 1 registro.
	$con = bancoMysqli();
	$sql = "SELECT * FROM $tabela WHERE ".$campo." = '$variavelCampo'";
	$query = mysqli_query($con,$sql);
	$campo = mysqli_fetch_array($query);
	return $campo;
}
function recuperaDados2($tabela)
{
	//retorna uma array com os dados de qualquer tabela. serve apenas para 1 registro.
	$con = bancoMysqli();
	$sql = "SELECT * FROM $tabela";
	$query = mysqli_query($con,$sql);
	$campo = mysqli_fetch_array($query);
	return $campo;
}
function contarDados($tabela,$campo,$variavelCampo)
{
	//retorna uma array com os dados de qualquer tabela. serve apenas para 1 registro.
	$con = bancoMysqli();
	$sql = "SELECT * FROM $tabela WHERE ".$campo." = '$variavelCampo'";
	$query = mysqli_query($con,$sql);
	$campo = mysqli_num_rows($query);
	return $campo;
}
function porcentagemDados($tabela)
{
	$con = bancoMysqli();

	$sqlTotal = mysqli_query($con,"SELECT * FROM $tabela");
	$total = mysqli_num_rows($sqlTotal);

	$sql = "SELECT * FROM $tabela WHERE status = 'aberto'";
	$query = mysqli_query($con,$sql);
	$tipo = mysqli_num_rows($query);


	// Faço o cálculo
	$porcentagem = $total - ($total / 100 * $tipo);

	return $total;
	return $tipo;
}

function checar($id)
{
	//funcao para imprimir checked do checkbox
	if($id == 1)
	{
		echo "checked";
	}
}

function analisaArray($array)
{
	//imprime o conteúdo de uma array
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

function recuperaUltimo($tabela)
{
	$con = bancoMysqli();
	$sql = "SELECT * FROM $tabela ORDER BY 1 DESC LIMIT 0,1";
	$query =  mysqli_query($con,$sql);
	$campo = mysqli_fetch_array($query);
	return $campo[0];
}

function retornaMes($mes)
{
	switch($mes)
	{
		case "01":
			return "Janeiro";
		break;
		case "02":
			return "Fevereiro";
		break;
		case "03":
			return "Março";
		break;
		case "04":
			return "Abril";
		break;
		case "05":
			return "Maio";
		break;
		case "06":
			return "Junho";
		break;
		case "07":
			return "Julho";
		break;
		case "08":
			return "Agosto";
		break;
		case "09":
			return "Setembro";
		break;
		case "10":
			return "Outubro";
		break;
		case "11":
			return "Novembro";
		break;
		case "12":
			return "Dezembro";
		break;
	}
}

function retornaMesExtenso($data)
{
	$meses = array('Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro');
	$data = explode("-", $dataMysql);
	$mes = $data[1];
	return $meses[($mes) - 1];
}

//retorna o dia da semana segundo um date(a-m-d)
function diaSemanaBase($data)
{
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);
	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );
	switch($diasemana)
	{
		case"0":
			$diasemana = "domingo";
		break;
		case"1":
			$diasemana = "segunda";
		break;
		case"2":
			$diasemana = "terca";
		break;
		case"3":
			$diasemana = "quarta";
		break;
		case"4":
			$diasemana = "quinta";
		break;
		case"5":
			$diasemana = "sexta";
		break;
		case"6":
			$diasemana = "sabado";
		break;
	}
	return "$diasemana";
}

// Função que valida e-mails
function validaEmail($email)
{
	/* Verifica se o email e valido */
	if (filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		/* Obtem o dominio do email */
		list($usuario, $dominio) = explode('@', $email);

		/* Faz um verificacao de DNS no dominio */
		if (checkdnsrr($dominio, 'MX') == 1)
		{
			return TRUE;
		}
		else
		{
		return FALSE;
		}
	}
	else
	{
		return FALSE;
	}
}

function recuperaInfoUsuario($idUser)
{
	//retorna uma array com indice 'numero' de registros e 'dados' da tabela
	$con = bancoMysqli();
	$sql = "SELECT * FROM users WHERE id = '$idUser'";
	$query = mysqli_query($con,$sql);
	$lista = mysqli_fetch_array($query);

	$sql_admin_user = "SELECT `users_id`, `admininstrators_id` FROM `administrators_users` WHERE `users_id` = $idUser";
	$query_admin_user = mysqli_query($con,$sql_admin_user);
	$adminUser = mysqli_fetch_array($query_admin_user);

	$campo = array(
		"idUser" => $lista['id'],
		"login" => $lista['login'],
		"local" => $lista['local'],
		"phone" => $lista['phone'],
		"email" => $lista['email'],
		"contact" => $lista['contact'],
		"address" => $lista['address'],
		"idRegion" => $lista['idRegion'],
		"operatingHours" => $lista['operatingHours'],
		"historicalBuilding" => $lista['historicalBuilding'],
		"dateCreated" => $lista['dateCreated'],
		"dateLastAccess" => $lista['dateLastAccess'],
		"idAdminUser" => $adminUser['admininstrators_id']
	);
	return $campo;
}


?>

