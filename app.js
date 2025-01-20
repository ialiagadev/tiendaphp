document.addEventListener('DOMContentLoaded', () => {
    loadFeaturedProducts();
});

function loadFeaturedProducts() {
    const products = [
        { id: 1, name: "Smartphone X", price: 599, image: "https://via.placeholder.com/300x200.png?text=Smartphone+X" },
        { id: 2, name: "Laptop Pro", price: 1299, image: "https://via.placeholder.com/300x200.png?text=Laptop+Pro" },
        { id: 3, name: "Auriculares Inalámbricos", price: 149, image: "https://via.placeholder.com/300x200.png?text=Auriculares" },
        { id: 4, name: "Smartwatch Elite", price: 299, image: "https://via.placeholder.com/300x200.png?text=Smartwatch" },
        { id: 5, name: "Tablet Ultra", price: 449, image: "https://via.placeholder.com/300x200.png?text=Tablet+Ultra" },
        { id: 6, name: "Cámara 4K", price: 799, image: "https://via.placeholder.com/300x200.png?text=Cámara+4K" },
        { id: 7, name: "Altavoz Inteligente", price: 129, image: "https://via.placeholder.com/300x200.png?text=Altavoz" },
        { id: 8, name: "Consola de Juegos", price: 499, image: "https://via.placeholder.com/300x200.png?text=Consola" }
    ];

    const productContainer = document.getElementById('featured-products');

    products.forEach(product => {
        const productElement = createProductElement(product);
        productContainer.appendChild(productElement);
    });
}

function createProductElement(product) {
    const col = document.createElement('div');
    col.className = 'col';

    col.innerHTML = `
        <div class="card h-100 product-card">
            <img src="${product.image}" class="card-img-top" alt="${product.name}">
            <div class="card-body">
                <h5 class="card-title">${product.name}</h5>
                <p class="card-text">$${product.price}</p>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary w-100" onclick="addToCart(${product.id})">Añadir al carrito</button>
            </div>
        </div>
    `;

    return col;
}

function addToCart(productId) {
    // Aquí iría la lógica para añadir el producto al carrito
    console.log(`Producto ${productId} añadido al carrito`);
    alert(`Producto añadido al carrito`);
}