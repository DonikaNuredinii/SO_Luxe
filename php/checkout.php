<?php
include 'header.php';
include 'db.php';
?>
<div class="checkout-container_ch">
    <form class="checkout-form_ch" onsubmit="return validateForm()">
        <div class="left-section_ch">
            <div class="contact_ch">
                <h2 class="h2ch">Contact</h2>
                <input type="email" name="email" placeholder="Your Email" required>
            </div>
            <div class="delivery_ch">
                <h2 class="h2ch">Delivery</h2>
                <select name="country" required>
                    <option value="">Select Country</option>
                    <option value="1">United States</option>
                    <option value="2">Canada</option>
                </select>
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="lastName" placeholder="Last Name" required>
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="zipCode" placeholder="Zip Code" required>
                <input type="text" name="city" placeholder="City" required>
                <input type="tel" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="payment_ch">
                <h2 class="h2ch">Payment</h2>
                <div class="payment-method_ch">
                    <input type="radio" name="paymentMethod" id="cashOnDelivery" value="cashOnDelivery" checked>
                    <label for="cashOnDelivery">
                        <i class="fas fa-hand-holding-usd"></i> Cash on Delivery
                    </label>
                </div>
            </div>

            <button type="submit" class="complete-order_ch">Complete Order</button>
        </div>

        <div class="right-section_ch">
            <div class="summary_ch">
                <h2 class="h2ch">Order Summary</h2>
                <ul>
                    <li>
                        <div class="item-image_ch">
                            <img src="https://via.placeholder.com/120" alt="Product Image">
                        </div>
                        <div class="item-details_ch">
                            <p>Product Name</p>
                            <p>1 x 50.00€</p>
                        </div>
                        <div class="item-total_ch">50.00€</div>
                    </li>
                </ul>
            </div>
            <div class="summary-details_ch">
                <p>Subtotal: 50.00€</p>
                <p>Shipping: Free</p>
                <p><strong>Total: 50.00€</strong></p>
            </div>
        </div>
    </form>
</div>
<?php include 'footer.php';?>
