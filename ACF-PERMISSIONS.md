# ACF Block Generator - Permissions Fix

## 🚨 **If You Get "Insufficient Permissions" Error**

When the ACF Block Generator tries to create new block templates, you might see:
```
Failed to save 'your_block.php': Insufficient permissions
```

## ⚡ **Quick Fix**

Run this command in Terminal:
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/resplast/wp-content/themes/resplast-theme
./fix-permissions.sh
```

## 🔧 **What This Does**

The script fixes permissions permanently by:

1. **Setting ownership**: `your_user:daemon` (both you and Apache can write)
2. **Directory permissions**: `775` (rwxrwxr-x)
3. **File permissions**: `664` (rw-rw-r--)
4. **Testing**: Verifies Apache can create files

## 📁 **File Structure After Fix**

```
templates/blocks/               # drwxrwxr-x your_user:daemon
├── hero_block.php             # -rw-rw-r-- your_user:daemon
├── testimonial.php            # -rw-rw-r-- your_user:daemon
└── global/                    # drwxrwxr-x your_user:daemon
    ├── certificate_block.php  # -rw-rw-r-- your_user:daemon
    └── get_in_touch_block.php  # -rw-rw-r-- your_user:daemon
```

## 🛡️ **Why This Happens**

- **XAMPP runs Apache as `daemon` user**
- **Your files are owned by your user account**
- **ACF Block Generator needs write access to create templates**
- **Solution**: Set group ownership to `daemon` with write permissions

## ✅ **Verification**

After running the script, you should see:
```
✅ Apache (daemon) can write files
🚀 ACF Block Generator should now work without permission errors!
```

## 🔄 **When to Run Again**

Run the fix script if:
- You get permission errors again
- You restore files from backup
- You move the site to a new server
- File permissions get reset

## 💡 **Alternative Quick Fix**

If the script doesn't work, try this manual command:
```bash
sudo chmod -R 777 /Applications/XAMPP/xamppfiles/htdocs/resplast/wp-content/themes/resplast-theme/templates/blocks/
```
*(Less secure but works immediately)*