#!/bin/bash

echo "🚀 FUMA - Football Tournament Management System Setup"
echo "=================================================="

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.1+ first."
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if Node.js is installed
if ! command -v node &> /dev/null; then
    echo "❌ Node.js is not installed. Please install Node.js first."
    exit 1
fi

echo "✅ Prerequisites check passed"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction

# Copy environment file
if [ ! -f .env ]; then
    echo "📋 Copying environment file..."
    cp .env.example .env
    echo "✅ Environment file created. Please configure your database settings."
else
    echo "✅ Environment file already exists"
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Build frontend assets
echo "🏗️ Building frontend assets..."
npm run build

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "Next steps:"
echo "1. Configure your database in .env file"
echo "2. Run: php artisan migrate"
echo "3. Run: php artisan db:seed"
echo "4. Start server: php artisan serve"
echo ""
echo "Default users after seeding:"
echo "- Admin: admin@fuma.com / password"
echo "- Organizer: organizer@fuma.com / password"
echo "- Manager: manager@fuma.com / password"
echo "- Committee: committee@fuma.com / password"
echo ""
echo "Happy coding! ⚽"
