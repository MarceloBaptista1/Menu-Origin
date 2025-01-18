<?php
    
    try {
        $servidor       = "mysql";
        $usuario        = "pizzaiolo"; 
        $senha          = "str0mP@ss";
        $nome_banco     = "pizzaiolo";

        $conn = mysqli_connect($servidor, $usuario, $senha, $nome_banco);
        if (!$conn) {
            throw new Exception("Falha na conexão: " . mysqli_connect_error());
        }

        //mysqli_close($conn);
        
    } catch (Exception $e) {
        echo 'Erro :: ' . $e->getMessage(), "\n";
    }

?>