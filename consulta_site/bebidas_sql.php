<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/MenuOrigin/medicina/hamburger_tempera.php";
    try{
        $array_bebidas = array();

        $sql_bebida = " 	SELECT
                                 p.id_produto 			AS id_bebida
                                ,p.nome_produto 		AS nome_bebida
                                ,p.descricao_produto	AS descricao_bebida
                                ,p.preco_venda_produto	AS preco_venda_bebida
                                ,p.foto_produto			AS foto_bebida
                            FROM
                                PRODUTOS p
                            WHERE
                                flag_bebida = 1
                            AND quantidade_estoque > 0	
                    ";
        $sql_bebida_q = mysqli_query($conn, $sql_bebida);
        if(!$sql_bebida_q){
            throw new Exception("Erro ao recuperar bebidas");
        }

        while($arr_dados = mysqli_fetch_assoc($sql_bebida_q)){
            $array_bebidas[] =  [
                                     'id_bebida'            => $arr_dados['id_bebida']
                                    ,'nome_bebida'          => $arr_dados['nome_bebida']
                                    ,'descricao_bebida'     => $arr_dados['descricao_bebida']
                                    ,'preco_venda_bebida'   => $arr_dados['preco_venda_bebida']
                                    ,'foto_bebida'          => $arr_dados['foto_bebida']
                                ];

        }
        // Retorno: $array_bebidase

    } catch (Exception $e) {
        echo $e->getMessage(). "\n";
    }
?>