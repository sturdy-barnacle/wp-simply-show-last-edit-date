# SB Show Last Edit Date

[![WordPress Plugin Version](https://img.shields.io/badge/version-1.0.2-blue.svg)](https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date)
[![WordPress](https://img.shields.io/badge/wordpress-5.2%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/php-7.4%2B-purple.svg)](https://php.net/)
[![License: GPL v2](https://img.shields.io/badge/License-GPL%20v2-red.svg)](http://www.gnu.org/licenses/gpl-2.0.txt)

A lightweight WordPress plugin that displays the last date and time a post or page was updated. Perfect for keeping your readers informed about content freshness and maintaining transparency about when your content was last modified.

## 📋 Table of Contents

- [Features](#-features)
- [Requirements](#-requirements)
- [Installation](#-installation)
- [Usage](#-usage)
- [Configuration](#-configuration)
- [Screenshots](#-screenshots)
- [Development](#-development)
- [Contributing](#-contributing)
- [Changelog](#-changelog)
- [License](#-license)
- [Author](#-author)
- [Support](#-support)

## ✨ Features

- **Automatic Display**: Automatically shows the last updated date and time on posts and pages
- **Customizable Position**: Choose to display the last edit info before or after your content
- **Global Controls**: Globally enable or disable last edit info for all posts and/or pages
- **Per-Post Override**: Individual control for each post/page to override global settings
- **Timezone Support**: Proper timezone support based on your WordPress settings
- **Translation Ready**: Fully internationalized and ready for translation
- **Lightweight**: Minimal performance impact with clean, efficient code
- **Secure**: Follows WordPress security best practices with proper sanitization and validation
- **Accessible**: Meets accessibility standards

## 📦 Requirements

- **WordPress**: 5.2 or higher
- **PHP**: 7.4 or higher

## 🚀 Installation

### From GitHub

1. Download the latest release from the [releases page](https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date/releases)
2. Extract the zip file to your WordPress plugins directory (`/wp-content/plugins/`)
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Configure the plugin settings under **Settings → SB Show Last Edit Date**

### Manual Installation

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date.git
```

Then activate the plugin through the WordPress admin interface.

## 📖 Usage

### Basic Usage

Once activated, the plugin automatically displays the last updated information on your posts and pages. By default, it shows:

```
Last updated on: January 15, 2025 at 3:45 pm
```

### Disabling for Specific Content

To disable the last edit info for a specific post or page:

1. Edit the post or page in WordPress
2. Find the **SB Show Last Edit Date** meta box in the sidebar
3. Check the **Disable Last Edit Info** checkbox
4. Update/publish the post

## ⚙️ Configuration

Access the plugin settings at **Settings → SB Show Last Edit Date** in your WordPress admin panel.

### Display Options

**Last Edit Info Position**
- **Before Content**: Display the last edit date before your post/page content
- **After Content**: Display the last edit date after your post/page content

### Global Disable Options

**Disable for all Posts**
- Check this option to hide the last edit date on all blog posts site-wide

**Disable for all Pages**
- Check this option to hide the last edit date on all pages site-wide

### Timezone Configuration

The plugin uses your WordPress timezone settings. To ensure accurate date/time display:

1. Go to **Settings → General**
2. Set your correct timezone in the **Timezone** field
3. Save changes

## 📸 Screenshots

### Settings Page
The plugin provides a clean and intuitive settings page where you can configure global options:
- Control the position of the last edit info
- Globally disable for all posts or pages

### Meta Box
Each post and page includes a convenient meta box in the editor sidebar:
- Quick toggle to disable last edit info for individual content
- Visual indicator of global settings status

### Frontend Display
The last updated information appears cleanly integrated with your content:
- Respects your theme's styling
- Includes custom CSS class for easy customization (`sb-last-updated`)

## 🛠️ Development

### Project Structure

```
wp-simply-show-last-edit-date/
├── sturdy-barnacle-last-edit/          # Main plugin directory
│   ├── css/                            # Stylesheets
│   │   └── sturdy-barnacle-last-edit.css
│   ├── includes/                       # PHP includes
│   │   ├── admin.php                   # Admin menu and links
│   │   ├── display-info.php            # Frontend display logic
│   │   ├── meta-box.php                # Meta box functionality
│   │   └── options-page.php            # Settings page
│   ├── languages/                      # Translation files
│   ├── license.txt                     # GPL license
│   ├── readme.txt                      # WordPress.org readme
│   ├── sturdy-barnacle-last-edit.php   # Main plugin file
│   └── uninstall.php                   # Cleanup on uninstall
├── LICENSE                             # Repository license
└── README.md                           # This file
```

### Customization

#### Custom Styling

You can customize the appearance of the last updated info by targeting the `.sb-last-updated` class in your theme's CSS:

```css
.sb-last-updated {
    font-size: 14px;
    color: #666;
    font-style: italic;
    padding: 10px;
    border-left: 3px solid #0073aa;
    background: #f7f7f7;
}
```

#### Programmatic Control

You can programmatically check or set the disable status using WordPress post meta:

```php
// Check if last edit info is disabled for a post
$is_disabled = get_post_meta($post_id, 'sb_disable_update_info', true);

// Disable last edit info for a post
update_post_meta($post_id, 'sb_disable_update_info', 'on');

// Enable last edit info for a post
update_post_meta($post_id, 'sb_disable_update_info', 'off');
```

### Filters and Actions

The plugin uses WordPress standard hooks. Key filter:

- `the_content` - Filters post/page content to add last updated info

### Translation

The plugin is translation-ready with the text domain `sturdy-barnacle-last-edit`. To translate:

1. Use [Poedit](https://poedit.net/) or similar tool
2. Create a `.po` file for your language
3. Compile to `.mo` file
4. Place in the `/languages/` directory

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. **Fork** the repository
2. **Create** a new branch (`git checkout -b feature/amazing-feature`)
3. **Make** your changes
4. **Test** thoroughly
5. **Commit** your changes (`git commit -m 'Add some amazing feature'`)
6. **Push** to the branch (`git push origin feature/amazing-feature`)
7. **Open** a Pull Request

### Development Guidelines

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- Ensure all strings are translatable
- Test with WordPress debug mode enabled
- Maintain backward compatibility
- Update documentation as needed

### Bug Reports

Found a bug? Please open an issue with:
- Clear description of the problem
- Steps to reproduce
- Expected vs actual behavior
- WordPress version, PHP version, and theme name
- Any relevant error messages

## 📝 Changelog

### Version 1.0.2
- Added PHP version requirement (7.4+) and version check
- Updated minimum WordPress version to 5.2
- Added proper plugin file headers
- Added `uninstall.php` for proper cleanup
- Enhanced CSS enqueuing with version parameter
- Improved code documentation
- Fixed untranslated strings for better i18n support
- Improved input handling with `wp_unslash()`
- Added `ABSPATH` checks to all files
- Enhanced URL escaping with `esc_url()`

### Version 1.0.1
- Added UI option to control position (before/after content)
- Enhanced input sanitization and validation
- Added proper text domain loading
- Added WordPress version compatibility check
- Fixed plugin action links implementation
- Improved error handling with try/catch
- Added WP_DEBUG support
- Updated compatibility to WordPress 6.7

### Version 1.0.0
- Initial release
- Basic functionality to display last edit date
- Meta box for per-post control
- Settings page for global options

[View full changelog](./sturdy-barnacle-last-edit/readme.txt)

## 📄 License

This project is licensed under the GNU General Public License v2.0 or later - see the [LICENSE](LICENSE) file for details.

## 👤 Author

**Kristina Quinones**

- Website: [kristinaquinones.com](https://kristinaquinones.com)
- GitHub: [@sturdy-barnacle](https://github.com/sturdy-barnacle)
- Support: [Ko-fi](https://ko-fi.com/kristinaq)

## 💬 Support

- **Documentation**: [WordPress.org Plugin Page](https://wordpress.org/plugins/sturdy-barnacle-last-edit/)
- **Issues**: [GitHub Issues](https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date/issues)
- **Discussions**: [GitHub Discussions](https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date/discussions)

---

**If you find this plugin useful, please consider:**
- ⭐ [Starring the repository](https://github.com/sturdy-barnacle/wp-simply-show-last-edit-date)
- 📝 [Leaving a review](https://wordpress.org/support/plugin/sturdy-barnacle-last-edit/reviews/)
- ☕ [Supporting development](https://ko-fi.com/kristinaq)

Made with ❤️ for the WordPress community