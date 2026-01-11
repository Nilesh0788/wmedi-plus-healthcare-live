# AI Coding Guidelines for WMedi-plus-Website WordPress Theme

## Architecture Overview
This is a custom WordPress theme for a medical/portfolio website with custom post types (services, projects, doctors). The theme follows standard WordPress theme structure with modular templates.

## Key Patterns
- **Template Structure**: All page templates must include `<?php get_header(); ?>` at the top and `<?php get_footer(); ?>` at the bottom
- **Custom Post Types**: Defined in `functions.php` - use `register_post_type()` for new content types
- **Asset Enqueueing**: Scripts and styles are enqueued in `custom_theme_scripts()` function using `wp_enqueue_script()` and `wp_enqueue_style()`
- **WordPress Loop**: Use standard loop structure: `if (have_posts()) : while (have_posts()) : the_post(); ... endwhile; endif;`

## Development Workflow
- Edit PHP templates directly in theme directory
- Custom functions go in `functions.php`
- Styles in `style.css`, scripts in `assets/js/main.js` (create if missing)
- Test in WordPress environment with custom post types active

## Conventions
- Use WordPress core functions (`the_content()`, `the_title()`, `wp_nav_menu()`)
- Register theme features in `custom_theme_setup()` hook
- Widget areas registered in `custom_theme_widgets_init()`
- Navigation menus: 'primary' and 'footer' locations

## File Structure
- `functions.php`: Theme setup, custom post types, enqueues
- `header.php`/`footer.php`: Site header/footer markup
- `index.php`: Homepage template
- `page.php`: Standard page template
- `style.css`: Theme styles and metadata

## Examples
- Creating new template: Start with `<?php get_header(); ?><div class="container"><?php the_content(); ?></div><?php get_footer(); ?>`
- Adding script: `wp_enqueue_script('script-name', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), null, true);`