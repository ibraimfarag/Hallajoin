// Create Order JavaScript

let selectedCustomer = null;
let cartItems = [];
let currentActivity = null;
let searchTimeout = null;

// Initialize
document.addEventListener('DOMContentLoaded', function () {
    initializeSearch();
    updateCartDisplay();
});

// Activity Search
function initializeSearch() {
    const searchInput = document.getElementById('activitySearch');

    searchInput.addEventListener('input', function (e) {
        clearTimeout(searchTimeout);
        const searchTerm = e.target.value.trim();

        if (searchTerm.length < 2) {
            showNoResults();
            return;
        }

        searchTimeout = setTimeout(() => {
            searchActivities(searchTerm);
        }, 500);
    });
}

function searchActivities(searchTerm) {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = '<div class="no-results"><i class="fa fa-spinner fa-spin"></i><p>Searching...</p></div>';

    $.ajax({
        url: window.location.origin + '/admin/module/report/booking/search-activities',
        method: 'POST',
        data: {
            search: searchTerm,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success && response.data.length > 0) {
                displaySearchResults(response.data);
            } else {
                showNoResults('No activities found');
            }
        },
        error: function () {
            showNoResults('Error loading activities');
        }
    });
}

function displaySearchResults(activities) {
    const resultsContainer = document.getElementById('searchResults');
    let html = '';

    activities.forEach(activity => {
        const imageUrl = activity.image || '/images/placeholder.jpg';
        const activityType = capitalizeFirst(activity.type);

        html += `
            <div class="activity-item" onclick="selectActivity(${activity.id}, '${activity.type}')">
                <span class="activity-badge">${activityType}</span>
                <img src="${imageUrl}" class="activity-image-thumb" alt="${activity.title}" onerror="this.src='/images/placeholder.jpg'">
                <div class="activity-info">
                    <div class="activity-title">${activity.title}</div>
                    <div class="activity-price">${activity.base_price} AED</div>
                </div>
            </div>
        `;
    });

    resultsContainer.innerHTML = html;
} function showNoResults(message = 'Start typing to search for activities') {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = `
        <div class="no-results">
            <i class="fa fa-search"></i>
            <p>${message}</p>
        </div>
    `;
}

// Select Activity and Load Details
function selectActivity(activityId, activityType) {
    // Get CSRF token
    const csrfToken = document.getElementById('csrf_token')?.value || '';

    $.ajax({
        url: window.location.origin + '/admin/module/report/booking/get-activity-details',
        method: 'POST',
        data: {
            activity_id: activityId,
            activity_type: activityType,
            _token: csrfToken
        },
        success: function (response) {
            if (response.success) {
                currentActivity = response.data;
                showActivityDetailsModal(response.data);
            }
        },
        error: function (xhr) {
            alert('Error loading activity details');
        }
    });
}

function showActivityDetailsModal(activity) {
    // Reset selected time slot
    window.selectedTimeSlot = null;

    // Set title
    document.getElementById('modalActivityTitle').textContent = activity.title;

    // Set image
    const imageHtml = activity.image
        ? `<img src="${activity.image}" alt="${activity.title}">`
        : `<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #9ca3af;">No Image</div>`;
    document.getElementById('modalActivityImage').innerHTML = imageHtml;

    // Set date options
    const dateInput = document.getElementById('selectedDate');
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    dateInput.value = activity.available_dates[0]?.date || today;

    // Add event listener for date change to load available times
    dateInput.onchange = function () {
        loadAvailableTimes(activity.id, activity.type, this.value);
    };

    // Load times for initially selected date
    loadAvailableTimes(activity.id, activity.type, dateInput.value);

    // Set person types
    displayPersonTypes(activity.person_types);

    // Calculate initial price
    calculateModalPrice();

    // Show modal
    document.getElementById('activityDetailsModal').style.display = 'flex';
}

// Load available times based on selected date
function loadAvailableTimes(activityId, activityType, selectedDate) {
    const csrfToken = document.getElementById('csrf_token')?.value || '';
    const container = document.getElementById('timeSelectionContainer');

    if (!container) {
        // If no time container in modal, skip
        return;
    }

    container.innerHTML = '<div class="loading-times"><i class="fa fa-spinner fa-spin"></i> Loading times...</div>';

    $.ajax({
        url: window.location.origin + '/admin/module/report/booking/get-available-times',
        method: 'POST',
        data: {
            activity_id: activityId,
            activity_type: activityType,
            date: selectedDate,
            _token: csrfToken
        },
        success: function (response) {
            if (response.success && response.data.available_times.length > 0) {
                displayTimeSlots(response.data.available_times);
            } else {
                container.innerHTML = '<div class="no-times">No available times for this date</div>';
            }
        },
        error: function () {
            container.innerHTML = '<div class="error-times">Error loading times</div>';
        }
    });
}

// Display time slot buttons
function displayTimeSlots(timeSlots) {
    const container = document.getElementById('timeSelectionContainer');
    let html = '<div class="time-slot-buttons">';

    timeSlots.forEach((slot, index) => {
        html += `
            <button type="button" class="time-slot-button" onclick="selectTimeSlot(${index}, '${slot.start}')">
                <span class="time-icon">🕐</span>
                <span class="time-range">${slot.display}</span>
            </button>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
}

// Select a time slot
function selectTimeSlot(index, timeValue) {
    // Remove selection from all buttons
    document.querySelectorAll('.time-slot-button').forEach(btn => {
        btn.classList.remove('selected');
    });

    // Select clicked button
    event.target.closest('.time-slot-button').classList.add('selected');

    // Store selected time
    window.selectedTimeSlot = timeValue;
}

function displayPersonTypes(personTypes) {
    const container = document.getElementById('personTypesContainer');
    let html = '';

    personTypes.forEach((type, index) => {
        html += `
            <div class="person-type-row">
                <div class="person-type-info">
                    <div class="person-type-name">${type.name}</div>
                    ${type.desc ? `<div class="person-type-desc">${type.desc}</div>` : ''}
                </div>
                <div class="person-type-price">${type.price} AED</div>
                <div class="quantity-control">
                    <button class="btn-qty" onclick="changeQuantity(${index}, -1)">
                        <i class="fa fa-minus"></i>
                    </button>
                    <span class="qty-value" id="qty-${index}">${type.min || 0}</span>
                    <button class="btn-qty" onclick="changeQuantity(${index}, 1)">
                        <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function changeQuantity(typeIndex, change) {
    const qtyElement = document.getElementById(`qty-${typeIndex}`);
    const personType = currentActivity.person_types[typeIndex];
    let currentQty = parseInt(qtyElement.textContent);
    let newQty = currentQty + change;

    // Validate min/max
    if (newQty < (personType.min || 0)) newQty = personType.min || 0;
    if (personType.max && newQty > personType.max) newQty = personType.max;

    qtyElement.textContent = newQty;
    calculateModalPrice();
}

function calculateModalPrice() {
    let total = 0;

    currentActivity.person_types.forEach((type, index) => {
        const qty = parseInt(document.getElementById(`qty-${index}`).textContent);
        total += qty * type.price;
    });

    document.getElementById('modalTotalPrice').textContent = total.toFixed(2);
}

function closeActivityDetails() {
    document.getElementById('activityDetailsModal').style.display = 'none';
    currentActivity = null;
}

// Add to Cart
function addToCart() {
    if (!currentActivity) return;

    const selectedDate = document.getElementById('selectedDate').value;
    if (!selectedDate) {
        alert('Please select a date');
        return;
    }

    // Check if time is required and selected
    const selectedTime = window.selectedTimeSlot;
    const timeContainer = document.getElementById('timeSelectionContainer');
    if (timeContainer && timeContainer.querySelector('.time-slot-buttons') && !selectedTime) {
        alert('Please select a time slot');
        return;
    }

    // Get selected person types
    const personTypes = [];
    let totalGuests = 0;

    currentActivity.person_types.forEach((type, index) => {
        const qty = parseInt(document.getElementById(`qty-${index}`).textContent);
        if (qty > 0) {
            personTypes.push({
                name: type.name,
                number: qty,
                price: type.price
            });
            totalGuests += qty;
        }
    });

    if (totalGuests === 0) {
        alert('Please select at least one guest');
        return;
    }

    // Calculate total
    const total = parseFloat(document.getElementById('modalTotalPrice').textContent);

    // Add to cart
    const cartItem = {
        id: Date.now(), // Temporary ID
        activity_id: currentActivity.id,
        activity_type: currentActivity.type,
        title: currentActivity.title,
        image: currentActivity.image,
        date: selectedDate,
        time: window.selectedTimeSlot || null,
        person_types: personTypes,
        total_guests: totalGuests,
        total: total
    };

    cartItems.push(cartItem);
    updateCartDisplay();
    closeActivityDetails();

    // Clear search
    document.getElementById('activitySearch').value = '';
    showNoResults();
}

// Cart Display
function updateCartDisplay() {
    const cartContainer = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const cartSummary = document.getElementById('cartSummary');
    const createOrderBtn = document.getElementById('createOrderBtn');

    cartCount.textContent = cartItems.length;

    if (cartItems.length === 0) {
        cartContainer.innerHTML = `
            <div class="empty-cart">
                <i class="fa fa-shopping-cart"></i>
                <p>Cart is empty</p>
            </div>
        `;
        cartSummary.style.display = 'none';
        createOrderBtn.disabled = true;
        return;
    }

    // Display cart items
    let html = '';
    cartItems.forEach((item, index) => {
        const guestBadges = item.person_types.map(pt =>
            `<span class="guest-badge">${pt.number}× ${pt.name}</span>`
        ).join('');

        const detailsHtml = item.time
            ? `<i class="fa fa-calendar"></i> ${formatDate(item.date)} <i class="fa fa-clock-o"></i> ${item.time}`
            : `<i class="fa fa-calendar"></i> ${formatDate(item.date)}`;

        html += `
            <div class="cart-item">
                <div class="cart-item-header">
                    <div class="cart-item-title">${item.title}</div>
                    <button class="btn-remove-item" onclick="removeFromCart(${index})">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
                <div class="cart-item-details">
                    ${detailsHtml}
                </div>
                <div class="cart-item-guests">
                    ${guestBadges}
                </div>
                <div class="cart-item-price">${item.total.toFixed(2)} AED</div>
            </div>
        `;
    });

    cartContainer.innerHTML = html;

    // Update summary
    const subtotal = cartItems.reduce((sum, item) => sum + item.total, 0);
    document.getElementById('itemsCount').textContent = cartItems.length;
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('totalAmount').textContent = subtotal.toFixed(2);
    cartSummary.style.display = 'block';

    // Enable create order button if customer is selected
    createOrderBtn.disabled = !selectedCustomer || cartItems.length === 0;
}

function removeFromCart(index) {
    if (confirm('Remove this item from cart?')) {
        cartItems.splice(index, 1);
        updateCartDisplay();
    }
}

// Customer Search
function searchCustomer() {
    const phone = document.getElementById('customerPhone').value.trim();

    if (!phone) {
        alert('Please enter phone number');
        return;
    }

    // Get CSRF token
    const csrfToken = document.getElementById('csrf_token')?.value || '';

    $.ajax({
        url: window.location.origin + '/admin/module/report/booking/search-customer',
        method: 'POST',
        data: {
            phone: phone,
            _token: csrfToken
        },
        success: function (response) {
            if (response.success) {
                displayCustomer(response.data);
            } else if (response.create_new) {
                if (confirm('Customer not found. Would you like to create a new customer?')) {
                    // TODO: Open create customer modal
                    alert('Create customer feature coming soon');
                }
            } else {
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            alert('Error searching for customer. Check console for details.');
        }
    });
}

function displayCustomer(customer) {
    selectedCustomer = customer;

    // Hide search box
    document.getElementById('customerPhone').value = customer.phone;

    // Show customer info
    const customerInfo = document.getElementById('customerInfo');
    const avatarHtml = customer.avatar
        ? `<img src="${customer.avatar}" alt="${customer.name}">`
        : customer.name.charAt(0).toUpperCase();

    document.getElementById('customerAvatar').innerHTML = avatarHtml;
    document.getElementById('customerName').textContent = customer.name;
    document.getElementById('customerPhoneDisplay').textContent = customer.phone;
    document.getElementById('customerEmail').textContent = customer.email || '';

    customerInfo.style.display = 'flex';

    // Show balance
    document.getElementById('walletAmount').textContent = `${customer.wallet_balance} AED`;
    document.getElementById('pointsAmount').textContent = customer.points;
    document.getElementById('customerBalance').style.display = 'flex';

    // Enable create order button if cart has items
    document.getElementById('createOrderBtn').disabled = cartItems.length === 0;
}

function clearCustomer() {
    selectedCustomer = null;
    document.getElementById('customerPhone').value = '';
    document.getElementById('customerInfo').style.display = 'none';
    document.getElementById('customerBalance').style.display = 'none';
    document.getElementById('createOrderBtn').disabled = true;
}

// Create Order
function createOrder() {
    if (!selectedCustomer) {
        alert('Please select a customer');
        return;
    }

    if (cartItems.length === 0) {
        alert('Cart is empty');
        return;
    }

    if (!confirm('Create order for ' + selectedCustomer.name + '?')) {
        return;
    }

    const btn = document.getElementById('createOrderBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating Order...';

    // Get CSRF token
    const csrfToken = document.getElementById('csrf_token')?.value || '';

    $.ajax({
        url: window.location.origin + '/admin/module/report/booking/create-order',
        method: 'POST',
        data: {
            customer_id: selectedCustomer.id,
            cart_items: cartItems,
            _token: csrfToken
        },
        success: function (response) {
            if (response.success) {
                alert('Order created successfully! The customer will receive a notification.');
                window.location.href = window.location.origin + '/admin/module/report/booking';
            } else {
                alert(response.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-check-circle"></i> CREATE ORDER';
            }
        },
        error: function (xhr) {
            console.error('Create order error:', xhr);
            alert('Error creating order');
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-check-circle"></i> CREATE ORDER';
        }
    });
}

// Helper Functions
function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}
