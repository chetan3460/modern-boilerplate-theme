#!/bin/bash

# ACF Block Generator - Fix Permissions Script
# Run this script whenever you have permission issues

echo "🔧 Fixing ACF Block Generator Permissions..."

# Get the theme directory
THEME_DIR="/Applications/XAMPP/xamppfiles/htdocs/resplast/wp-content/themes/resplast-theme"
BLOCKS_DIR="$THEME_DIR/templates/blocks"

# Create directories if they don't exist
echo "📁 Creating directories..."
mkdir -p "$BLOCKS_DIR"
mkdir -p "$BLOCKS_DIR/global"

# Set ownership (user:daemon so both you and Apache can write)
echo "👤 Setting ownership..."
sudo chown -R $(whoami):daemon "$BLOCKS_DIR"
sudo chown -R $(whoami):daemon "$THEME_DIR/templates"

# Set directory permissions (755 = rwxr-xr-x)
echo "📂 Setting directory permissions..."
sudo find "$BLOCKS_DIR" -type d -exec chmod 775 {} \;

# Set file permissions (664 = rw-rw-r--)
echo "📄 Setting file permissions..."
sudo find "$BLOCKS_DIR" -type f -name "*.php" -exec chmod 664 {} \;

# Set default permissions for new files
echo "🔐 Setting umask for Apache..."
echo "# ACF Block Generator permissions" | sudo tee -a /Applications/XAMPP/xamppfiles/etc/httpd.conf > /dev/null

echo "✅ Permissions fixed!"
echo "📝 Summary:"
echo "   - Directories: 775 (rwxrwxr-x)"
echo "   - PHP files: 664 (rw-rw-r--)"
echo "   - Owner: $(whoami):daemon"

# Test permissions
echo "🧪 Testing permissions..."
if sudo -u daemon touch "$BLOCKS_DIR/test_write.tmp" 2>/dev/null; then
    echo "✅ Apache (daemon) can write files"
    rm -f "$BLOCKS_DIR/test_write.tmp"
else
    echo "❌ Apache (daemon) cannot write files"
    echo "💡 Try running: sudo chmod 777 $BLOCKS_DIR"
fi

echo ""
echo "🚀 ACF Block Generator should now work without permission errors!"