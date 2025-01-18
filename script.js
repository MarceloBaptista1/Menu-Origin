const menu 					= document.getElementById("menu")
const addressInput 			= document.getElementById("address")
const addressWarn			= document.getElementById("address-warn")
let cart 					= [];


$(addressInput).on("input", (event) => {
	let inputValue = event.target.value

	if (inputValue != "") {
		addressInput.classList.remove("border-red-500")
		addressWarn.classList.add("hidden")
	}
});


// Abri o Modal Do carrinho
function openCartModal(){
	updateCartModal()
	$("#cart-modal").css('display', 'flex')
}


function closeCartModal(){
	$("#cart-modal").css('display', 'none')
}


function assembleCart(){
	let parentButton = event.target.closest(".add-to-cart-btn")
	
	if (parentButton) {
		let name 	= parentButton.getAttribute("data-name")
		let price 	= parseFloat(parentButton.getAttribute("data-price"))
		let id 		= parseInt(parentButton.getAttribute("data-id"))

		addToCart(name, price, id)
	}

}


function alertNewProduct(name_product) {
	alertToast(`${name_product} Adicionado ao carrinho`, "DarkOrange", "#000000")
}


function addToCart(name, price, id) {
	let existingItem = cart.find((item) => item.name === name)

	if (existingItem) {
		existingItem.quantity += 1;
	} else {
		cart.push({
			name,
			price,
			quantity: 1,
			id,
		});
	}
	updateCartModal()
}


function updateCartModal() {
    let total = 0

    // Limpa os itens do carrinho no DOM
    $("#cart-items").text("")

    // Itera pelos itens do carrinho
    cart.forEach((item, index) => {
        const $cartItemElement = $('<div>', {
            class: 'flex mb-4 flex-col justify-between'
        })
		
        const $content = $(`
            <div class="flex items-center justify-between mb-8" id="item_${item.id}"> 
                <div>
                    <div class="mb-1 flex items-center">
						<span class="font-bold">${item.name}</span>
                        <button type="button" class="btn-remove ml-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
								<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
							</svg>
						</button>      
                    </div>
                    <p>Quantidade: <span id="quantityProductsCartUpdated-${index}">${item.quantity}</span></p>
                    <p class="font-medium" id="total-${index}">
                        Valor: R$ ${(item.price * item.quantity).toFixed(2)}
                    </p>
                </div>
                <div class="flex items-center gap-5">
                    <button type="button" class="fa-solid fa-minus btn-minus"></button>
                    <input 
                        type="number" 
                        id="quantityProductsCart-${index}" 
                        name="quantityProductsCart" 
                        min="1" 
                        max="99"
                        value="${item.quantity}"
                        class="form-control text-center"
                    >
                    <button type="button" class="fa-solid fa-plus btn-plus"></button>
                </div>
            </div>
        `)

        $content.find('.btn-remove').on('click', () => removeCartItem(item.id, item.name))
        $content.find('.btn-minus').on('click', () => decreaseQuantity(index))
        $content.find('.btn-plus').on('click', () => {
            item.quantity++; 
            updateCartModal()
        });

        // Input de quantidade
        $content.find('input[name="quantityProductsCart"]').on('change', function () {
            const newQuantity = parseInt($(this).val(), 10)
            if (newQuantity >= 1 && newQuantity <= 99) {
                item.quantity = newQuantity 
                updateCartModal()
            }
        });

        total += item.price * item.quantity

        $cartItemElement.append($content)
        $("#cart-items").append($cartItemElement)
    });

    $("#cart-total").text(total.toLocaleString("pt-BR", { style: "currency", currency: "BRL" }))
    $("#cart-count").text(cart.reduce((acc, item) => acc + item.quantity, 0))
}



function editItemModal(index) {
	let item = cart[index]

	$("#modalItemName").text(item.name)
	$("#modalItemQuantity").val(item.quantity)

	$("#editModal").removeClass("hidden")
	$("#editModal").addClass("flex")
}


function closeModal() {
	$("#editModal").addClass("hidden")
}


// Diminuir a quantidade
function decreaseQuantity(index) {
	if (cart[index].quantity > 1) {
		cart[index].quantity -= 1
		updateCartModal()
	}
}


// Aumentar a quantidade
function increaseQuantity(index) {
	cart[index].quantity += 1
	updateCartModal()
}


// Atualizar a quantidade no input 
function updateQuantity(index) {
	let quantityInput 	= document.getElementById(`quantityProductsCart-${index}`)
	let newQuantity 	= parseInt(quantityInput.value, 10)

	if (newQuantity >= 1) {
		cart[index].quantity = newQuantity
		updateCartModal()
	} else {
		quantityInput.value = 1
		cart[index].quantity = 1
		updateCartModal()
	}
}

// Finalizar pedido
function finishOrder(){
	if (!checkOpenRestaurant){ 
		alertToast("Ops, estabelecimento fechado no momento!", "#ef4444")
		return
	}

	if (cart.length == 0) return;

	if (addressInput.value == "") {
		addressWarn.classList.remove("hidden")
		addressInput.classList.add("border-red-500")
		return;
	}

	let cartInfo = cart
		.map((item) => {
			return `🛒 *${item.name}* \n Quantidade: ${item.quantity} \n Preço: R$${item.price}\n----------------------------`; 
		})
		.join("\n");
	
	let phone	= "16997466829"
	let total	= cart.reduce((sum, item) => sum + item.quantity * item.price, 0).toFixed(2)
		
	cartInfo.push(`💵 *Total: * R$${total}`);
	cartInfo.push(`📍 *Endereço:* ${addressInput.value}`)

	let message	= encodeURIComponent(cartInfo)

	window.open(
		`https://wa.me/${phone}?text=${message}`,
		"_blank"
	)

	cart = []
	updateCartModal()
}


function removeCartItem(itemId, itemName) {
	cart.splice(itemId, 1)
	$(`#item_${itemId}`).remove()
	alertToast(`${itemName} Removido com Sucesso!`, "green")
	updateCartModal()
}


function checkOpenRestaurant() {
	let hora 		= new Date().getHours()
	let isMobile	= window.innerWidth <= 768

	return hora >= 10 && hora < 23
}

if (!checkOpenRestaurant) {
	$("#date-span").removeClass("bg-red-500")
	$("#date-span").addClass("bg-green-600")
} else {
	$("#date-span").removeClass("bg-red-500")
	$("#date-span").addClass("bg-green-600")
}

function alertToast(mensagem, background, Textcolor){
	let isMobile = window.innerWidth <= 768
	Toastify({
		text: `${mensagem}`,
		duration: 3000,
		close: true,
		gravity: "bottom",
		position: "right",
		stopOnFocus: true,
		style: {
			fontSize: isMobile ? "14px" : "16px",
			padding: isMobile ? "10px" : "15px",
			borderRadius: "15px",
			background: `${background}`,
			color: Textcolor != '' ? Textcolor : undefined
		},
	}).showToast()
}