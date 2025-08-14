<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agronomy Farms Help Center</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Tailwind CSS CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Use a nature-inspired font from Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F4E1; /* A soft, earthy background color */
        }
        
        /* Custom styling for the main title to feel more prominent and natural */
        .page-title {
            color: #1A5319; /* A deep, forest green */
            border-bottom: 2px solid #6A994E; /* A lighter, grass-like green border */
        }
        
        /* Styling for the feature cards */
        .feature-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* Custom styling for the FAQ items to look like cards */
        .faq-item {
            border-left: 4px solid #6A994E;
            transition: background-color 0.2s;
        }
        .faq-item:hover {
            background-color: #F0F4E8;
        }
    </style>
</head>
<body class="bg-[#F8F4E1] flex justify-center py-12 px-4 sm:px-6 lg:px-8">

    <div class="page-content w-full max-w-4xl bg-white p-8 rounded-lg shadow-xl">
        <!-- Main title with a help icon -->
        <h1 class="page-title text-4xl sm:text-5xl font-bold pb-4 mb-8 flex items-center gap-4">
            <i class="fas fa-question-circle text-[#6A994E]"></i> Help Center
        </h1>
        
        <!-- Welcome message -->
        <p class="page-description text-gray-700 text-lg mb-10 leading-relaxed">
            Welcome to the Agronomy Farms Help Center. Here you'll find answers to frequently asked questions, 
            guides on how to use our platform, and resources to help you with your agricultural business.
        </p>

        <!-- --- Frequently Asked Questions Section --- -->
        <h2 class="text-3xl font-bold text-[#1A5319] mt-12 mb-6">Frequently Asked Questions</h2>
        
        <div class="space-y-6">
            <!-- FAQ Item 1 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold text-[#1A5319] mb-2">How do I create an account?</h3>
                <p class="text-gray-600">
                    To create an account, click on the "Create Account" link in the top right corner of the page. 
                    Fill in your details, including your name, email address, and a secure password. Once submitted, 
                    you'll be able to log in and start using our platform.
                </p>
            </div>
            
            <!-- FAQ Item 2 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold text-[#1A5319] mb-2">How do I place an order?</h3>
                <p class="text-gray-600">
                    To place an order, browse our products and add items to your cart. When you're ready to checkout, 
                    go to your cart and follow the steps to complete your purchase. You'll need to provide shipping 
                    information and payment details to complete your order.
                </p>
            </div>
            
            <!-- FAQ Item 3 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold text-[#1A5319] mb-2">What payment methods do you accept?</h3>
                <p class="text-gray-600">
                    We accept various payment methods, including credit/debit cards (Visa, MasterCard), 
                    mobile payments (M-Pesa, Tigo Pesa), and bank transfers. All payments are processed 
                    securely through our platform.
                </p>
            </div>
            
            <!-- FAQ Item 4 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold text-[#1A5319] mb-2">How long does delivery take?</h3>
                <p class="text-gray-600">
                    Delivery times vary based on your location and the product. Within Tanzania, most orders 
                    arrive within 3-7 business days. International orders may take 7-14 business days. 
                    You'll receive tracking information once your order ships.
                </p>
            </div>
            
            <!-- FAQ Item 5 -->
            <div class="faq-item bg-gray-50 p-6 rounded-lg shadow-sm">
                <h3 class="text-xl font-semibold text-[#1A5319] mb-2">What is your return policy?</h3>
                <p class="text-gray-600">
                    We offer a 30-day return policy for most products. If you're not satisfied with your purchase, 
                    contact our support team to initiate a return. Please note that some agricultural products 
                    may have specific return restrictions.
                </p>
            </div>
        </div>

        <!-- --- Other Resources Section --- -->
        <h2 class="text-3xl font-bold text-[#1A5319] mt-12 mb-6">Other Resources</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Customer Support Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-headset"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">24/7 Customer Support</h3>
                <p class="feature-description text-gray-600">
                    Our dedicated support team is available around the clock to assist you with any questions or issues.
                </p>
            </div>

            <!-- Knowledge Base Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">Knowledge Base</h3>
                <p class="feature-description text-gray-600">
                    Access our comprehensive guides and tutorials to make the most of our platform.
                </p>
            </div>

            <!-- Video Tutorials Card -->
            <div class="feature-card bg-white p-6 rounded-xl shadow-md border-t-4 border-t-[#6A994E]">
                <div class="feature-icon text-4xl text-[#6A994E] mb-4">
                    <i class="fas fa-video"></i>
                </div>
                <h3 class="feature-title text-xl font-semibold text-[#1A5319] mb-2">Video Tutorials</h3>
                <p class="feature-description text-gray-600">
                    Watch step-by-step video guides on using our platform and managing your agricultural business.
                </p>
            </div>
        </div>
    </div>
</body>
</html>