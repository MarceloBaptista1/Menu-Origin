<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/Menu-Origin/consulta_site/lanches_sql.php"?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/Menu-Origin/consulta_site/bebidas_sql.php"?>

<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css"/>
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
		<link rel="stylesheet" href="./styles/output.css" />
		<link rel="stylesheet" href="./styles/styles.css" />
		<title>Burger Bom</title>
		
	</head>

	<body>
		<header class="w-full h-[420px] bg-zinc-900 bg-home bg-cover bg-center">
			<div class="w-full h-full flex flex-col justify-center items-center">
				<img src="./assets/hamb-1.png" alt="Logo devburger" class="w-32 h-32 rounded-full shadow-lg hover:scale-110 duration-200" style="cursor: pointer"/>
				<h1 class="text-4xl mt-4 mb-2 font-bold text-white">
					Burguer Bom
				</h1>
				<span class="text-white font-medium">
					Rua Bola, 671 Santos - SP
				</span>

				<div class="bg-green-600 px-4 py-1 rounded-lg mt-5" id="date-span">
					<span class="text-white font-medium">
						Seg á Dom - 18:00 as 23:00
					</span>
				</div>
			</div>
		</header>

		<h2 class="md:text-3xl text-2xl font-bold text-center mt-9 mb-6">
			Conheça Nossos Burguers
		</h2>

		<div id="menu" onclick="assembleCart()">
			<main class="grid grid-cols-1 md:grid-cols-2 gap-7 md:gap-10 mx-auto max-w-7xl px-2 mb-16">
			<?php foreach($array_lanches as $cha_lanches => $val_lanches){?>
					<div class="flex gap-2">
						<img
							src="<?='./'.$val_lanches['foto_lanche'] ?? '' ?>"
							alt="<?=$val_lanches['nome_lanche']?>"
							class="w-28 h-28 rounded-md hover:scale-110 hover:rotate-2 duration-300"
							style="cursor: pointer"
							onclick="alerta();"
						/>

						<div class="w-full">
							<p class="font-bold"><?=$val_lanches['nome_lanche']?></p>
							<p class="text-sm">
								<?=$val_lanches['descricao_lanche']?>
							</p>

							<div
								class="flex items-center gap-2 justify-between mt-3""
							>
								<p class="font-bold">R$ <?=$val_lanches['preco_venda_lanche']?></p>
								<button
									class="bg-gray-900 px-5 rounded add-to-cart-btn"
									data-name="<?=$val_lanches['nome_lanche']?>"
									data-price="<?=$val_lanches['preco_venda_lanche']?>"
									data-id="<?=$val_lanches['id_lanche']?>"
									onclick="alertNewProduct('<?=$val_lanches['nome_lanche']?>');"
								>
									<i
										class="fa fa-cart-plus text-lg text-white"
									></i>
								</button>
							</div>
						</div>
					</div>
				<?php }?>
			</main>

			<div class="mx-auto max-w-7xl px-2 my-2">
				<h2 class="font-bold text-3xl">Bebidas</h2>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-7 md:gap-10 mx-auto max-w-7xl px-2 mb-16" id="menu2">
				<?php foreach($array_bebidas as $cha_bebidas => $val_bebidas){?>
					<div class="flex gap-2 w-full">
						<img src="<?='./'.$val_bebidas['foto_bebida'] ?? '' ?>" alt="<?=$val_bebidas['nome_bebida']?>" class="w-28 h-28 rounded-md hover:scale-110 hover:rotate-2 duration-300"/>
						<div class="w-full">
							<p class="font-bold"><?=$val_bebidas['nome_bebida']?></p>

							<div class="flex items-center gap-2 justify-between mt-5">
								<p class="font-bold">R$<?=$val_bebidas['preco_venda_bebida']?></p>
								<button
									class="bg-gray-900 px-5 rounded add-to-cart-btn"
									data-name="<?=$val_bebidas['nome_bebida']?>"
									data-price="<?=$val_bebidas['preco_venda_bebida']?>"
									data-id="<?$val_bebidas['id_bebida']?>"
									onclick="alertNewProduct('<?=$val_bebidas['nome_bebida']?>');"
								>
									<i class="fa fa-cart-plus text-lg text-white"></i>
								</button>
							</div>
						</div>
					</div>
				<?php }?>
			</div>
		</div>

		<div class="bg-black/70 w-full h-full fixed top-0 left-0 z-[99] items-center justify-center hidden" id="cart-modal">
			<div class="bg-white p-5 rounded-md min-w-[90%] md:min-w-[600px]">
				<h2 class="font-bold text-center text-2xl mb-2">
					Meu Carrinho
				</h2>
				<div id="cart-items" class="flex justify-between mb-2 flex-col" style="max-height: 400px; overflow: auto"></div>
				<hr style="border-top: 0.01rem solid darkorange" />
				<p class="font-bold" style="margin-top: 15px">
					Total: <span id="cart-total">0.00</span>
				</p>

				<div class="mt-4">
					<form>
						<label class="font-bold flex" for="payment"
							>Forma de pagamento:
						</label>
						<select name="payment" id="payment" required class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" style="border-radius: 0.25rem; padding: 10px">
							<option value="Cartao">Cartão De Crédito</option>
							<option value="Cartao">Cartão De Débito</option>
							<option value="Pix">Pix</option>
							<option value="Dinheiro">Dinheiro</option>
						</select>
					</form>
				</div>
				<p class="font-bold mt-4">Endereço Para Entrega:</p>
				<input type="text" placeholder="Digite Seu Endereço..." id="address" class="w-full border-2 p-1 rounded my-1 border-gray-300"/>
				<p class="text-red-600 hidden" id="address-warn">
					Digite Seu Endereço!!
				</p>
				<div class="flex items-center justify-between mt-5 w-full">
					<button id="close-modal-btn" onclick="closeCartModal()">Fechar</button>
					<button
						id="checkout-btn"
						class="bg-green-600 text-white px-4 py-1 rounded"
						onclick="finishOrder()"
					>
						Concluir Pedido
					</button>
				</div>
			</div>
		</div>

		<div class="bg-black/70 w-full h-full fixed top-0 left-0 z-[99] items-center justify-center hidden" id="editModal">
			<div class="bg-white p-5 rounded-md min-w-[90%] md:min-w-[600px]">
				<h2 class="text-2xl text-center font-bold mb-3">
					Editar Lanche
				</h2>
				<p id="modalItemName" class="mb-2"></p>
				<label for="modalItemQuantity">Quantidade:</label>
				<input type="number" id="modalItemQuantity" value="1" min="1" max="99" class="border border-gray-300 p-2 rounded"/>
				<div class="mt-4 flex justify-between gap-2">
					<button type="button" onclick="closeModal()">
						Cancelar
					</button>
					<button class="bg-green-600 text-white px-4 py-1 rounded"type="button" onclick="saveChanges()">
						Salvar
					</button>
				</div>
			</div>
		</div>

		<footer class="w-full bg-red-500 py-2 fixed bottom-0 z-40 flex items-center justify-center">
			<button class="flex items-center gap-2 text-white font-bold" id="cart-btn" onclick="openCartModal()">
				(<span id="cart-count"> 0 </span>) Veja Meu Carrinho
				<i class="fa fa-cart-plus text-lg text-white"></i>
			</button>
		</footer>

		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
		<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
		<script src="script.js"></script>
	</body>
</html>