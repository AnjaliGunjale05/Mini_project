// Alert message (for success)
function showAlert(message) {
    alert(message);
}

// Confirm delete (for admin)
function confirmDelete() {
    return confirm("Are you sure you want to delete?");
}

// Update cart quantity
function updateQuantity(id, action) {
    let qtyInput = document.getElementById('qty-' + id);
    let qty = parseInt(qtyInput.value);

    if (action === 'increase') {
        qty++;
    } else if (action === 'decrease' && qty > 1) {
        qty--;
    }

    qtyInput.value = qty;
}

// Simple search filter (frontend)
function filterProducts() {
    let input = document.getElementById("search").value.toLowerCase();
    let cards = document.getElementsByClassName("product-card");

    for (let i = 0; i < cards.length; i++) {
        let name = cards[i].getElementsByTagName("h3")[0].innerText.toLowerCase();

        if (name.includes(input)) {
            cards[i].style.display = "block";
        } else {
            cards[i].style.display = "none";
        }
    }
}

// Auto hide flash message
setTimeout(() => {
    let msg = document.getElementById('flash-message');
    if (msg) {
        msg.style.display = 'none';
    }
}, 3000);