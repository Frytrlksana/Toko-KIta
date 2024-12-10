<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Bakery Shop</title>
    <link rel="stylesheet" href="{{ asset('css/styles5.css') }}">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
            <nav>
                <ul>
                    <li><a href="#">Menu</a></li>
                    <li><a href="#">My Order</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Log Out</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <div id="menu">
            <h3>Menu</h3>
            <div class="menu-item">
                <h4>Macaron</h4>
                <p>Price: 60k</p>
                <button class="decrease" data-item="Macaron">-</button>
                <span class="quantity" data-item="Macaron">0</span>
                <button class="increase" data-item="Macaron">+</button>
            </div>
            <div class="menu-item">
                <h4>Kue Jahe</h4>
                <p>Price: 65k</p>
                <button class="decrease" data-item="Kue Jahe">-</button>
                <span class="quantity" data-item="Kue Jahe">0</span>
                <button class="increase" data-item="Kue Jahe">+</button>
            </div>
        </div>

        <div id="cart">
            <h3>Cart</h3>
            <ul id="cart-items">
                <!-- Contoh item cart -->
                <li>
                    Macaron (2 x 60k)
                    <button class="remove-item" data-item="Macaron">Hapus</button>
                </li>
            </ul>
            <div>
                <strong>Total: </strong><span id="cart-total">120</span>k
            </div>
        </div>


    </div>
</body>
</html>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const cart = {}; // Objek untuk menyimpan state menu di cart

    // Fungsi untuk memperbarui UI jumlah di menu
    function updateMenuQuantityUI(itemName, quantity) {
        const quantityElement = document.querySelector(`.quantity[data-item="${itemName}"]`);
        if (quantityElement) {
            quantityElement.textContent = quantity;
        }
    }

    // Fungsi untuk memperbarui UI cart
    function updateCartUI() {
    const cartItems = document.getElementById("cart-items");
    const cartTotal = document.getElementById("cart-total");
    cartItems.innerHTML = ""; // Kosongkan cart
    let total = 0;

    for (const [itemName, item] of Object.entries(cart)) {
        const li = document.createElement("li");
        li.innerHTML = `
            ${itemName} (${item.quantity} x ${item.price}k)
            <button class="remove-item" data-item="${itemName}">Hapus</button>
        `;
        cartItems.appendChild(li);
        total += item.quantity * item.price; // Hitung total harga
    }

    cartTotal.textContent = total; // Perbarui total harga

    // Event listener untuk tombol "Hapus" di cart
    document.querySelectorAll(".remove-item").forEach(button => {
        button.addEventListener("click", () => {
            const itemName = button.getAttribute("data-item");
            removeFromCart(itemName);
        });
    });
}

function removeFromCart(itemName) {
    if (cart[itemName]) {
        delete cart[itemName]; // Hapus item dari cart
        updateMenuQuantityUI(itemName, 0); // Reset jumlah di menu ke 0
        updateCartUI(); // Perbarui UI cart
    }
}

    // Fungsi untuk menambah jumlah menu
    function increaseMenu(itemName, price) {
        if (cart[itemName]) {
            cart[itemName].quantity += 1;
        } else {
            cart[itemName] = { price: price, quantity: 1 };
        }
        updateMenuQuantityUI(itemName, cart[itemName].quantity); // Update UI jumlah di menu
        updateCartUI(); // Update UI cart
    }

    // Fungsi untuk mengurangi jumlah menu
    function decreaseMenu(itemName) {
        if (cart[itemName]) {
            cart[itemName].quantity -= 1;
            if (cart[itemName].quantity <= 0) {
                delete cart[itemName]; // Hapus item jika jumlah 0
            }
            updateMenuQuantityUI(itemName, cart[itemName]?.quantity || 0); // Update UI jumlah di menu
            updateCartUI(); // Update UI cart
        }
    }

    // Event listener untuk tombol tambah di menu
    document.querySelectorAll(".increase").forEach(button => {
        button.addEventListener("click", () => {
            const itemName = button.getAttribute("data-item");
            const price = parseInt(button.parentElement.querySelector("p").textContent.replace("Price: ", "").replace("k", ""));
            increaseMenu(itemName, price);
        });
    });

    // Event listener untuk tombol kurang di menu
    document.querySelectorAll(".decrease").forEach(button => {
        button.addEventListener("click", () => {
            const itemName = button.getAttribute("data-item");
            decreaseMenu(itemName);
        });
    });
});

</script>