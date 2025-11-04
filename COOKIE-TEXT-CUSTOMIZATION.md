# Cookie Consent Text Customization Guide

Now you have **3 easy ways** to customize the cookie banner text without touching JavaScript!

---

## **Method 1: Edit in PHP (Simplest)**

Edit `inc/cookie-helpers.php` around line 178:

```php
function resplast_localize_cookie_consent() {
    wp_localize_script('main', 'cookieConsentText', array(
        'title' => __('Your custom title here', 'resplast'),
        'description' => __('Your custom description here', 'resplast'),
        'policyText' => __('Learn more in our', 'resplast'),
        'policyLink' => __('Cookie Policy', 'resplast'),
        'policyUrl' => home_url('/cookie-policy'),
        'acceptButton' => __('Accept', 'resplast'),
        'rejectButton' => __('Decline', 'resplast'),
        'acceptLabel' => __('Accept all cookies', 'resplast'),
        'rejectLabel' => __('Reject all cookies', 'resplast'),
    ));
}
```

**No rebuild needed!** Just edit PHP and refresh.

---

## **Method 2: Use WordPress Filter (Most Flexible)**

Add this to your `functions.php` or a custom plugin:

```php
add_filter('resplast_cookie_consent_config', function($config) {
    $config['title'] = 'Custom banner title';
    $config['description'] = 'Custom description text';
    $config['acceptButton'] = 'I Agree';
    $config['rejectButton'] = 'No Thanks';
    return $config;
});
```

---

## **Method 3: ACF Options Page (User-Friendly)**

Create an ACF Options page for non-technical users:

```php
// Add to functions.php
if( function_exists('acf_add_options_page') ) {
    acf_add_options_sub_page(array(
        'page_title'  => 'Cookie Consent Settings',
        'menu_title'  => 'Cookie Consent',
        'parent_slug' => 'options-general.php',
    ));
}

// Update the localization function
function resplast_localize_cookie_consent() {
    $title = get_field('cookie_banner_title', 'option') ?: 'We use cookies...';
    $description = get_field('cookie_banner_description', 'option') ?: 'We use cookies and...';
    
    wp_localize_script('main', 'cookieConsentText', array(
        'title' => $title,
        'description' => $description,
        'policyText' => get_field('cookie_policy_text', 'option') ?: 'Learn more in our',
        'policyLink' => get_field('cookie_policy_link_text', 'option') ?: 'Cookie Policy',
        'policyUrl' => home_url('/cookie-policy'),
        'acceptButton' => get_field('cookie_accept_button', 'option') ?: 'Accept All',
        'rejectButton' => get_field('cookie_reject_button', 'option') ?: 'Reject All',
        'acceptLabel' => __('Accept all cookies', 'resplast'),
        'rejectLabel' => __('Reject all cookies', 'resplast'),
    ));
}
```

Then create ACF fields:
- `cookie_banner_title` (Text)
- `cookie_banner_description` (Textarea)
- `cookie_policy_text` (Text)
- `cookie_policy_link_text` (Text)
- `cookie_accept_button` (Text)
- `cookie_reject_button` (Text)

---

## **Quick Examples**

### Example 1: Short & Simple
```php
'title' => 'This site uses cookies',
'description' => 'We use cookies to improve your experience.',
'acceptButton' => 'OK',
'rejectButton' => 'No',
```

### Example 2: Corporate/Formal
```php
'title' => 'Cookie Notice',
'description' => 'This website uses cookies to ensure you get the best experience on our website. By continuing to browse, you agree to our use of cookies.',
'acceptButton' => 'I Understand',
'rejectButton' => 'Opt Out',
```

### Example 3: Friendly/Casual
```php
'title' => '🍪 Cookie Time!',
'description' => 'Hey! We use cookies to make your experience awesome. Cool with that?',
'acceptButton' => 'Sounds Good!',
'rejectButton' => 'No Thanks',
```

---

## **Translation Support**

All text uses WordPress `__()` function, so you can translate via:

1. **WordPress Translation Plugins** (Loco Translate, WPML, Polylang)
2. **Create .po/.mo files** for your language
3. **Use conditional logic** for multi-language:

```php
function resplast_localize_cookie_consent() {
    $locale = get_locale();
    
    $titles = [
        'en_US' => 'We use cookies...',
        'es_ES' => 'Usamos cookies...',
        'fr_FR' => 'Nous utilisons des cookies...',
    ];
    
    $title = $titles[$locale] ?? $titles['en_US'];
    
    wp_localize_script('main', 'cookieConsentText', array(
        'title' => $title,
        // ... rest of config
    ));
}
```

---

## **Change Cookie Policy Link**

```php
'policyUrl' => home_url('/privacy-policy'), // Change to your page
```

Or use an external link:
```php
'policyUrl' => 'https://yoursite.com/legal/cookies',
```

---

## **Testing Your Changes**

1. Edit the PHP file
2. Clear browser cookies: DevTools → Application → Cookies → Delete `resplast_cookie_consent`
3. Refresh page
4. Banner shows with your new text!

**No npm build required** when changing text via PHP! ✨

---

## **Benefits of This Approach**

✅ **No JavaScript editing** - change text in PHP  
✅ **No rebuild needed** - instant updates  
✅ **Translation ready** - WordPress i18n support  
✅ **User-friendly** - can create admin UI with ACF  
✅ **Filterable** - other plugins can modify text  
✅ **Secure** - HTML is escaped automatically  

---

## **Current Default Text**

```
Title: "We use cookies to give you the best possible experience on our website."

Description: "We use cookies and similar technologies to enhance site performance, 
personalize your experience, and gather anonymous data about how you use our site. 
We also work with trusted partners to understand general trends like interests and 
online behavior. You can manage your preferences anytime."

Policy Link: "Learn more in our Cookies Policy"

Buttons: "Accept All" / "Reject All"
```

---

## **Need Help?**

- Text not showing? Check browser console for errors
- Want custom styling? Edit `assets/css/cookie-consent.css`
- Need more complex logic? Use Method 2 (filters)
