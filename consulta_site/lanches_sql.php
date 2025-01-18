<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/Cardapio2/medicina/hamburger_tempera.php";
    try{
        $array_lanches = array();

        $sql_lanche = "    SELECT 
                                     l.id_lanche
                                    ,l.nome_lanche
                                    ,l.descricao_lanche
                                    ,l.preco_venda_lanche
                                    ,l.foto_lanche
                                    ,GROUP_CONCAT(p.id_produto ORDER BY p.id_produto SEPARATOR ', ') AS id_ingredientes
                                FROM 
                                    LANCHES l
                                    JOIN LANCHES_PRODUTOS lp ON lp.id_lanche = l.id_lanche
                                    JOIN PRODUTOS p ON p.id_produto = lp.id_produto
                                GROUP BY
                                    lp.id_lanche
                            ";
        $sql_lanche_q = mysqli_query($conn, $sql_lanche);
        if(!$sql_lanche_q){
            throw new Exception("Erro ao recuperar lanches");
        }

        while($arr_dados = mysqli_fetch_assoc($sql_lanche_q)){
            $array_id_ingredientes   = explode(',', $arr_dados['id_ingredientes']);
        
            $sql_ingredientes = "   SELECT
                                         p.id_produto
                                        ,p.nome_produto
                                        ,p.preco_venda_produto
                                        ,p.foto_produto
                                        ,p.flag_bebida
                                        ,lp.quantidade_produto as quantidade_ingrediente
                                    FROM
                                        PRODUTOS p 
                                        JOIN LANCHES_PRODUTOS lp ON lp.id_produto = p.id_produto
                                    WHERE
                                        p.id_produto in(".implode(",", $array_id_ingredientes).")
                                ";
            $sql_ingredientes_q = mysqli_query($conn, $sql_ingredientes);
            if(!$sql_ingredientes_q){
                throw new Exception("Erro ao recuperar ingredientes");
            }

            $array_ingredientes = array();
            while($sql_ingredientes_a = mysqli_fetch_assoc($sql_ingredientes_q)){
                $array_ingredientes[] = $sql_ingredientes_a;
            }

            $array_lanches[] =  [
                                     'id_lanche'            => $arr_dados['id_lanche']
                                    ,'nome_lanche'          => $arr_dados['nome_lanche']
                                    ,'descricao_lanche'     => $arr_dados['descricao_lanche']
                                    ,'preco_venda_lanche'   => $arr_dados['preco_venda_lanche']
                                    ,'foto_lanche'          => $arr_dados['foto_lanche']
                                    ,'ingredientes'         => $array_ingredientes
                                ];

        }
        // Retorno: $array_lanchese

    } catch (Exception $e) {
        echo $e->getMessage(). "\n";
    }
?>