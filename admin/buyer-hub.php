<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agronomy Farms Buyer Hub</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Use a nature-inspired font from Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            /* A soft, earthy background color */
            background-color: #F8F4E1; 
        }

        /* Custom styling to enhance Tailwind defaults */
        .page-content {
            max-width: 1000px;
        }

        /* Styling for the main title to feel more prominent and natural */
        .page-title {
            color: #1A5319; /* A deep, forest green */
            border-bottom: 2px solid #6A994E; /* A lighter, grass-like green border */
        }

        /* Custom styling for the feature cards */
        .feature-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* Styling for the step-by-step list */
        .step-list li {
            list-style: none;
            counter-increment: step-counter;
        }

        /* Add custom numbers to the list items with a natural color */
        .step-list li::before {
            content: counter(step-counter);
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            margin-right: 15px;
            border-radius: 50%;
            background-color: #6A994E; /* Green circle background */
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-[#F8F4E1] flex justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="page-content w-full bg-white p-8 rounded-lg shadow-xl">
        <!-- Main title with an icon for visual interest -->
        <h1 class="page-title text-4xl sm:text-5xl font-bold pb-4 mb-8 flex items-center gap-4">
            <i class="fas fa-shopping-cart text-[#6A994E]"></i> Buyer Hub
        </h1>
        
        <!-- A friendly, descriptive paragraph -->
        <p class="page-description text-gray-700 text-lg mb-10 leading-relaxed">
            Welcome to the Agronomy Farms Buyer Hub. This is your central place for managing purchases, 
            tracking orders, and accessing resources to help you make informed buying decisions.
        </p>
        
        <!-- Features section using a responsive grid layout -->
        <div class="features grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Order Management Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">Order Management</h3>
                <p class="feature-description text-gray-600">
                    Track your orders, view order history, and manage returns all in one place.
                </p>
            </div>
            
            <!-- Trade Assurance Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">Trade Assurance</h3>
                <p class="feature-description text-gray-600">
                    Secure payments and quality guarantees for all your purchases.
                </p>
            </div>
            
            <!-- Exclusive Deals Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-percentage"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">Exclusive Deals</h3>
                <p class="feature-description text-gray-600">
                    Access special discounts and promotions available only to registered buyers.
                </p>
            </div>
        </div>
        
        <!-- Buyer Resources Section -->
        <h2 class="text-3xl font-bold text-[#1A5319] mt-12 mb-8">Buyer Resources</h2>
        
        <!-- Resources section using a responsive grid layout -->
        <div class="features grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <!-- Buying Guides Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">Buying Guides</h3>
                <p class="feature-description text-gray-600">
                    Comprehensive guides to help you choose the right agricultural products for your needs.
                </p>
            </div>
            
            <!-- Product Demos Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-video"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">Product Demos</h3>
                <p class="feature-description text-gray-600">
                    Video demonstrations showing how to use various agricultural products and equipment.
                </p>
            </div>
            
            <!-- Cost Calculators Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-calculator"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">Cost Calculators</h3>
                <p class="feature-description text-gray-600">
                    Tools to help you calculate costs and determine the best value for your purchases.
                </p>
            </div>
        </div>
        
        <!-- How to Buy section with a stylized numbered list -->
        <h2 class="text-3xl font-bold text-[#1A5319] mt-12 mb-6">How to Buy</h2>
        
        <ul class="step-list space-y-6">
            <li class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-2xl font-semibold text-[#1A5319]">Create an Account</h3>
                <p class="text-gray-600 mt-2">Register as a buyer to access all features of our marketplace.</p>
            </li>
            <li class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-2xl font-semibold text-[#1A5319]">Browse Products</h3>
                <p class="text-gray-600 mt-2">Search for agricultural products by category, supplier, or specific requirements.</p>
            </li>
            <li class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-2xl font-semibold text-[#1A5319]">Add to Cart</h3>
                <p class="text-gray-600 mt-2">Select products and quantities that meet your needs and add them to your cart.</p>
            </li>
            <li class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-2xl font-semibold text-[#1A5319]">Checkout Securely</h3>
                <p class="text-gray-600 mt-2">Complete your purchase using our secure payment system with Trade Assurance protection.</p>
            </li>
            <li class="bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-2xl font-semibold text-[#1A5319]">Track Your Order</h3>
                <p class="text-gray-600 mt-2">Monitor your order status from processing to delivery through your Buyer Hub dashboard.</p>
            </li>
        </ul>
    </div>

</body>
</html>