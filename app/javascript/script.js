// Function to show the modal with the first image and description
const showModal = (images, description) => {
    let currentImageIndex = 0;

    const modal = document.getElementById('product-modal');
    const modalImage = document.getElementById('product-image-modal');
    const modalDescription = document.getElementById('product-description');
    const prevButton = document.getElementById('prev-image');
    const nextButton = document.getElementById('next-image');

    // Function to update the image
    const updateImage = () => {
        modalImage.src = images[currentImageIndex];
    };

    // Show the first image and description
    if (images.length > 0) {
        updateImage();
    } else {
        modalImage.src = 'https://via.placeholder.com/150';
    }
    modalDescription.textContent = description;

    // Event listeners for the buttons
    prevButton.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex > 0) ? currentImageIndex - 1 : images.length - 1;
        updateImage();
    });

    nextButton.addEventListener('click', () => {
        currentImageIndex = (currentImageIndex < images.length - 1) ? currentImageIndex + 1 : 0;
        updateImage();
    });

    // Check if to show the buttons
    prevButton.style.display = ((currentImageIndex+1)-images.length > 1) ? 'block' : 'none';
    nextButton.style.display = (images.length-(currentImageIndex+1) > 0) ? 'block' : 'none';

    // Show the modal
    modal.style.display = "block";
};

// Check if any Modal is already open function
const isAnyModalOpen = () => {
    const modals = document.getElementsByClassName('modal');
    for (let i = 0; i < modals.length; i++) {
        if (modals[i].style.display === "block") {
            return true;
        }
    }
    return false;
}

document.addEventListener('DOMContentLoaded', () => {
    const span = document.getElementsByClassName("close")[0];
    const cartIcon = document.getElementById('cart');
    const accountIcon = document.getElementById('account');

    // Event listener for opening modal on view options click
    document.querySelectorAll('.view-options').forEach(button => {
        button.addEventListener('click', async (e) => {
            if (isAnyModalOpen()) {
                return;
            }
            const modal = document.getElementById('price-modal');// Check if any Modal is already open
            const prices = JSON.parse(e.target.dataset.productPrices);

            // Populate the modal with prices
            const priceOptionsDiv = document.getElementById('price-options');
            priceOptionsDiv.innerHTML = ''; // Clear previous content
            prices.forEach(price => {
                priceOptionsDiv.innerHTML += "<div class=\"price-option\">"
                if (price.recurring) {
                    let interval = price.recurring.interval;
                    if (price.recurring.interval_count > 1) {
                        interval += "s";
                        interval = price.recurring.interval_count + " " + interval;
                    }
                    priceOptionsDiv.innerHTML += `
                        <p>${price.value / 100} ${price.currency} per ${interval}</p>
                        <form action="models/buy_product.php" method="POST">
                            <input type="hidden" name="priceId" value="${price.id}">
                            <button type="submit">Subscribe now</button>
                        </form>
                    `;
                } else {
                    priceOptionsDiv.innerHTML += `
                        <p>${price.value / 100} ${price.currency}</p>
                        <form action="models/add_to_cart.php" method="POST">
                            <input type="hidden" name="priceId" value="${price.id}">
                            <button type="submit">Add to Basket</button>
                        </form>
                        <form action="models/buy_product.php" method="POST">
                            <input type="hidden" name="priceId" value="${price.id}">
                            <button type="submit">Buy now</button>
                        </form>
                    `;
                }
                priceOptionsDiv.innerHTML += "</div>";
            });

            // Display the modal
            modal.style.display = "block";
        });
    });

    // Event listener for opening modal on product click
    document.querySelectorAll('.product').forEach(product => {
        product.addEventListener('click', (e) => {
            if (isAnyModalOpen()) {
                return;
            }
            if (e.target.closest('form') || e.target.classList.contains('view-options')) {
                return;
            }
            const images = JSON.parse(e.currentTarget.dataset.images);
            const description = e.currentTarget.dataset.productDescription;
            showModal(images, description);
        });
    });

    // Event listener for closing modal
    // Close modal when clicking on the close button
    document.querySelectorAll('.close').forEach(closeButton => {
        closeButton.addEventListener('click', () => {
            // hide modal correlating to the close button
            closeButton.parentElement.parentElement.style.display = "none";
        });
    });

    // Event listener for closing modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }

    // Event listener for basket icon to navigate to cart page
    if (cartIcon) {
        cartIcon.addEventListener('click', () => {
            window.location.href = 'view_cart.php';
        });
    }

    // Event listener for account icon to navigate to account page
    if (accountIcon) {
        accountIcon.addEventListener('click', () => {
            window.location.href = 'account.php';
        });
    }

    // Form validation
    const forms = document.querySelectorAll('form');
    const inputFields = document.querySelectorAll('input[type="text"], input[type="password"], input[type="email"], textarea');

    // Create account form validation
    let createAccountForm = document.getElementById('create-account-form');
    if (createAccountForm) {
        createAccountForm.addEventListener('submit', function(e) {
            let password = document.getElementById('password');
            let confirmPassword = document.getElementById('reenter-password');
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match');
                confirmPassword.focus();
            }
        });
    }

    // Update password form validation
    let updatePasswordForm = document.getElementById('update_password_form');
    if (updatePasswordForm) {
        updatePasswordForm.addEventListener('submit', function(e) {
            let password = document.getElementById('new_password');
            let confirmPassword = document.getElementById('confirm_password');
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match');
                confirmPassword.focus();
            }
        });
    }

    // Submit button
    forms.forEach(function(form) {
        form.addEventListener('submit', function (e) {
            let incomplete = false;
            // Get all input fields in the form
            let inputs = form.querySelectorAll('input[type="text"], input[type="password"], input[type="email"], textarea');
            inputs.forEach(function(input) {
                if (input.value === '') {
                    incomplete = true;
                    input.style.backgroundColor = "rgba(255, 0, 0, 0.2)";
                }
            });
            if (incomplete) {
                e.preventDefault();
                alert('Please fill in all fields');
            }
        });
    });

    // Focus event listener
    if (inputFields) {
        inputFields.forEach(function(inputField) {
            inputField.addEventListener('focus', function() {
                inputField.style.backgroundColor = "white";
            });
        });
    }

});
