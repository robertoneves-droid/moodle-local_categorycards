# Moodle Plugin: Category Cards (local_categorycards)

A Moodle local plugin designed to replace the default plain list of course categories with elegant, responsive, and customizable visual cards. This plugin enhances site navigation and visual aesthetics, allowing administrators and category managers to define custom cover images, background colors, and text colors for each category.

## 🌟 Features

* **Visual Cards Layout:** Transforms boring standard lists of categories into a modern grid of visual cards.
* **Fully Responsive Grid:** Supports dynamic responsive layouts (Auto) or fixed column grids (3 or 4 columns).
* **Per-Category Customization:** Configure individual background colors, text colors, and cover images.
* **Modern Fallback Icons:** If no cover image is uploaded for a category, a clean SVG graduation icon is automatically displayed.
* **Hidden Category Support:** Gracefully dims cards for categories that are hidden/invisible, maintaining visual consistency for administrators while keeping them hidden from students.

## ⚙️ System Requirements

* **Moodle Version:** 4.3 or higher.
* **PHP Version:** PHP 8.1 or higher (matching Moodle 4.3+ requirements).
* **Database:** Compatible with PostgreSQL and MySQL/MariaDB.

## 🚀 Installation

1. Download the plugin files.
2. Put the `categorycards` folder in your Moodle's `local/` directory. The path must be: `[moodle_directory]/local/categorycards/`.
3. Log in to your Moodle site as an Administrator.
4. Navigate to **Site administration > Notifications** to trigger the database installation.
5. Upgrade the Moodle database.
6. Navigate to **Site administration > Development > Purge all caches** to ensure all Javascript (AMD) and styles (CSS) are fully loaded.

## ⚙️ Global Settings

Go to **Site administration > Plugins > Local plugins > Category Cards** to configure:
* **Enable Plugin:** Enable or disable the card layout globally.
* **Layout Columns:** Choose between "Responsive (Auto)", "3 Columns", or "4 Columns" for desktop devices.

## 🛠️ How to Customize Category Cards

1. Navigate to the course category page you want to customize.
2. In the category settings navigation/menu, click on **Category Card Custom Settings** (Configurações Personalizadas do Card da Categoria).
3. Set your desired colors and upload a cover image.
4. Save the changes.

## 👥 Author & License
* **Author:** Roberto Neves
* **License:** Licensed under the GNU GPL v3 or later.
