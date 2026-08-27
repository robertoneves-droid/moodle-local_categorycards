/**
 * Javascript AMD module for local_categorycards.
 *
 * @module     local_categorycards/cards
 * @copyright  2026 Moodle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function() {
    return {
        /**
         * Convert a hex color to rgba with transparency.
         *
         * @param {string} hex
         * @param {number} alpha
         * @return {string}
         */
        hexToRgba: function(hex, alpha) {
            hex = hex.replace('#', '');
            if (hex.length === 3) {
                hex = hex.split('').map(function(hexChar) {
                    return hexChar + hexChar;
                }).join('');
            }
            var r = parseInt(hex.substring(0, 2), 16);
            var g = parseInt(hex.substring(2, 4), 16);
            var b = parseInt(hex.substring(4, 6), 16);
            return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
        },

        /**
         * Initialize dynamic DOM replacement for categories.
         *
         * @param {Object} categoryData JSON map of category metadata (colors, images).
         */
        init: function(categoryData, columns) {
            // Wait for DOM to be fully loaded.
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    this.executeReplacement(categoryData, columns);
                }.bind(this));
            } else {
                this.executeReplacement(categoryData, columns);
            }
        },

        /**
         * Select, parse, and replace default category markup with card grid.
         *
         * @param {Object} categoryData
         */
        executeReplacement: function(categoryData, columns) {
            var self = this;
            var elements = [];

            // Find all category links in the main content area.
            var mainContent = document.querySelector('#region-main, [role="main"], #page-content');
            var containerSearchScope = mainContent || document;
            var categoryLinks = containerSearchScope.querySelectorAll('a[href*="course/index.php?categoryid="]');

            categoryLinks.forEach(function(link) {
                // Ignore nested links, breadcrumbs, search results, or menus.
                if (link.closest('.breadcrumb, .nav, .dropdown-menu, .menu, #nav-drawer')) {
                    return;
                }

                // Parse category ID from URL.
                var url;
                try {
                    url = new URL(link.href);
                } catch (e) {
                    return;
                }
                var catId = url.searchParams.get('categoryid');
                if (!catId) {
                    return;
                }

                // Locate the category wrapper container.
                var container = link.closest('.coursebox, .category, .combo-list-item, .list-group-item, li');
                if (!container) {
                    return;
                }

                // Avoid processing the same category container multiple times.
                if (container.getAttribute('data-categorycards-processed')) {
                    return;
                }
                container.setAttribute('data-categorycards-processed', 'true');

                // Extract category name.
                var name = link.textContent.trim();
                // Filter out subcategory counters if present (e.g. "Graduação (12)").
                name = name.replace(/\s*\(\d+\)\s*$/, '');

                elements.push({
                    id: catId,
                    name: name,
                    url: link.href,
                    container: container,
                    link: link
                });
            });

            if (elements.length === 0) {
                return;
            }

            // Create Grid container.
            var grid = document.createElement('div');
            grid.className = 'local-categorycards-grid';
            if (columns === '3') {
                grid.classList.add('local-categorycards-grid-3');
            } else if (columns === '4') {
                grid.classList.add('local-categorycards-grid-4');
            }

            elements.forEach(function(cat) {
                // Retrieve custom metadata or use defaults.
                var custom = categoryData[cat.id] || {};
                var bgcolor = custom.bgcolor || '#0f5b9e';
                var fontcolor = custom.fontcolor || '#ffffff';
                var imageurl = custom.imageurl;

                // Determine if category is hidden.
                var isHidden = false;
                if (custom.hasOwnProperty('visible') && (custom.visible === 0 || custom.visible === '0' || custom.visible === false)) {
                    isHidden = true;
                } else if (
                    cat.container.classList.contains('dimmed') || 
                    cat.container.classList.contains('dimmed_category') ||
                    cat.link.classList.contains('dimmed') ||
                    cat.link.classList.contains('dimmed_category') ||
                    cat.container.querySelector('.dimmed, .dimmed_category')
                ) {
                    isHidden = true;
                }

                // Create Card Anchor.
                var card = document.createElement('a');
                card.className = 'local-categorycard';
                card.href = cat.url;
                if (isHidden) {
                    card.classList.add('dimmed');
                }

                // Card Top Area.
                var cardTop = document.createElement('div');
                cardTop.className = 'local-categorycard-top';

                if (imageurl) {
                    var img = document.createElement('img');
                    img.className = 'local-categorycard-image';
                    img.src = imageurl;
                    img.alt = cat.name;
                    cardTop.appendChild(img);
                } else {
                    // Fallback modern graduation/class SVG icon.
                    cardTop.innerHTML = 
                        '<svg class="local-categorycard-fallback" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">' +
                            '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/>' +
                            '<path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/>' +
                        '</svg>';
                }
                card.appendChild(cardTop);

                // Card Bottom Area.
                var cardBottom = document.createElement('div');
                cardBottom.className = 'local-categorycard-bottom';
                cardBottom.style.backgroundColor = isHidden ? self.hexToRgba(bgcolor, 0.5) : bgcolor;
                cardBottom.style.color = fontcolor;

                var title = document.createElement('h3');
                title.className = 'local-categorycard-title';
                title.style.color = fontcolor;
                title.textContent = cat.name;

                cardBottom.appendChild(title);
                card.appendChild(cardBottom);

                // Append card to grid.
                grid.appendChild(card);
            });

            // Clean replacement logic to avoid disrupting surrounding markup.
            var firstContainer = elements[0].container;
            var listWrapper = firstContainer.parentNode;

            // If the parent is a standard list structure, we can safely hide the entire list.
            if (listWrapper.matches('ul, .list-group, .categories, .subcategories')) {
                listWrapper.style.display = 'none';
                listWrapper.parentNode.insertBefore(grid, listWrapper);
            } else {
                // Otherwise, we hide individual items and insert grid at the top item's position.
                firstContainer.parentNode.insertBefore(grid, firstContainer);
                elements.forEach(function(cat) {
                    cat.container.style.display = 'none';
                });
            }
        }
    };
});
