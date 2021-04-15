<?php




include '../bd/conexao.php';
session_start();
$con = bancoMysqli();
$protocolo = $_SESSION['editar'];
$nome = $_POST['nome1'];
$user = $_POST['username1'];
$email = $_POST['email1'];
$empresa = $_POST['empresa'];
$categoria = $_POST['categoria1'];
$motivo = $_POST['motivo1'];
$prioridade = $_POST['prioridade1'];
$descricao = $_POST['descricao1'];




$query = "UPDATE relatorio SET nome = '$nome' , user = '$user' , email = '$email' , empresa = '$empresa', categoria = '$categoria', motivo = '$motivo', prioridade = '$prioridade', descricao = '$descricao' WHERE protocolo = '$protocolo'";
mysqli_query($con, $query);

if(mysqli_affected_rows($con) != 0){
                echo "
                    <META HTTP-EQUIV=REFRESH CONTENT = '0;URL=http://localhost/px/examples/./relatorio.php'>
                    <script type='text/javascript'>
                        alert('Usuario atualizado com Sucesso.');
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