<?php




include '../bd/conexao.php';
$con = bancoMysqli();
$protocolo = date("Ymd") . mt_rand();
$nome = $_POST['nome'];
$user = $_POST['username'];
$email = $_POST['email'];
$empresa = $_POST['empresa'];
$categoria = $_POST['categoria'];
$motivo = $_POST['motivo'];
$prioridade = $_POST['prioridade'];
$descricao = $_POST['descricao'];
$status = "Aberto";



$query = "INSERT INTO `relatorio` (`protocolo` ,`nome` , `user`, `email` , `empresa`, `categoria`, `motivo`, `prioridade`, `descricao`, `status`) 
VALUES ('$protocolo', '$nome', '$user', '$email', '$empresa', '$categoria', '$motivo', '$prioridade', '$descricao', '$status')";
mysqli_query($con, $query);

if(mysqli_affected_rows($con) != 0){
                echo "
                    <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http://localhost/px/examples/./relatorio.php'>
                    <script type='text/javascript'>
                        alert('Usuario cadastrado com Sucesso.');
                    </script>
                ";    
            }else{
                echo "
                    <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http://localhost/px/examples/./relatorio.php'>
                    <script type='text/javascript'>
                        alert('O Usuario não foi cadastrado com Sucesso.');
                    </script>
                ";    
            }

?>