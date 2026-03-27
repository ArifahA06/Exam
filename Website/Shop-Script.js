const productContainer = document.querySelector(".product-list");
const isProductDetailPage = document.querySelector(".product-detail");

if (productContainer) {
    displayProducts();
} else if (isProductDetailPage) {
    displayProductDetail();
}

function displayProducts() {
    displayProducts.forEach(product => {
        const productCard = document.createElement("div");
        productCard.classList.add("product-card");
        productCard.innerHTML = `
            <div class="img-box">
                <img src="${product.colors[0].mainImage}">
            </div>
            <h2 class="title">${product.title}</h2>
            <span class="price">${product.price}</span>
        `;
        productContainer.appendChild(productCard);

        const imgBox = productCard.querySelector(".img-box");
        imgBox.addEventListener("click", () => {
            sessionStorage.setItem("selectedProduct", JSON.stringify(product));
            window.location.href = "product.html";
        });
    });
}

function displayProductDetail() {
    const selectedProduct = JSON.parse(sessionStorage.getItem("selectedProduct"));

    const titleEl = document.querySelector(".title");
    const priceEl = document.querySelector(".price");
    const descriptionEl = document.querySelector(".description");
    const mainImageContainer = document.querySelector(".main-img");
    const thumbnailContainer = document.querySelector(".thumbnail-list");
    const addToCartBtn = document.querySelector(".add-to-cart");
}