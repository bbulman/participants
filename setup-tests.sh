#!/bin/bash

# Workshop.io Participant - Test Setup Script
# This script helps set up the testing environment

echo "🧪 Workshop.io Participant - Test Setup"
echo "========================================"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer not found. Please install Composer first."
    echo "   Visit: https://getcomposer.org/download/"
    exit 1
fi

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP not found. Please install PHP 8.0 or higher."
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "✅ PHP Version: $PHP_VERSION"

# Install dependencies
echo ""
echo "📦 Installing PHPUnit and dependencies..."
composer install

# Check if PHPUnit is installed
if [ ! -f "vendor/bin/phpunit" ]; then
    echo "❌ PHPUnit installation failed"
    exit 1
fi

PHPUNIT_VERSION=$(./vendor/bin/phpunit --version | head -n1)
echo "✅ $PHPUNIT_VERSION"

# Check database configuration
echo ""
echo "🗄️  Database Configuration:"
echo "   Host: localhost"
echo "   Test Database: u773681277_timer_test"
echo "   User: u773681277_timer"
echo "   Charset: utf8mb4"

# Check if MySQL is available
if command -v mysql &> /dev/null; then
    echo "✅ MySQL client found"
    
    # Test database connection (this will likely fail without proper credentials)
    echo ""
    echo "📋 Database Setup Instructions:"
    echo "   1. Create test database:"
    echo "      CREATE DATABASE u773681277_timer_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    echo ""
    echo "   2. Run schema on test database:"
    echo "      mysql -u u773681277_timer -p u773681277_timer_test < database/schema.sql"
    echo ""
    echo "   3. Update credentials in phpunit.xml if needed"
else
    echo "⚠️  MySQL client not found. Install MySQL to run database tests."
fi

# Run basic tests
echo ""
echo "🧪 Running Basic Tests..."
./vendor/bin/phpunit tests/unit/BasicTest.php --testdox

# Show test commands
echo ""
echo "🚀 Test Commands:"
echo "   Run all tests:        composer test"
echo "   Run unit tests:       composer test-unit"
echo "   Run integration:      composer test-integration"
echo "   Run with coverage:    composer test-coverage"
echo "   Run specific test:    ./vendor/bin/phpunit tests/unit/BasicTest.php"

echo ""
echo "📖 Documentation:"
echo "   Test README:          tests/README.md"
echo "   Project docs:         CLAUDE.md"
echo "   Database schema:      schema.md"

echo ""
echo "✅ Test setup complete!"
echo "   Note: Database tests will require proper MySQL credentials and test database setup."