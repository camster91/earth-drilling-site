# Honeycomb Creative - WordPress Boilerplate Theme

A boilerplate theme for WordPress with an emphasis on simplicity and reuse. By default, all pages except the homepage are rendered with index.php. Use front-page.php for your homepage, then you can use index.php for everything else. If it gets weird, refer to [The Flowchart Of Template Witchcraft and Wizardry](http://codex.wordpress.org/File:wp-template-hierarchy.jpg) to determine which template file to add; probably archive-{post_type}.php or single-{post_type}.php

Less is more. Only create as many custom templates as you need. Think modularly.

This is most definitely a work in progress and opportunities for improvement exist. Feel free to make pull requests...or just nag Conor.

A few notes/philosophy about this:

1. This theme has no images, no styles, and just bare js files. There's barely any markup too. The idea is, this should be just enough to function as a WordPress theme and nothing more. It's designed to let you just jump in with your own markup and do your thing.
2. Except for the homepage, every single page is rendered with index.php. Using front-page.php for your homepage lets you do this. Archives, search results, pages, posts... they're all rendered with index.php, with the loop partial displaying the actual content. This is done to suggest a build style where you use as few templates as possible, and reuse as much as possible. Use the template flow chart to figure out what to add if you need to, but I feel really strongly about reuse being something with huge productivity gains, as well as end result consistency gains. You'll likely add several new template files, but I wanted to create a theme that worked with as few templates as possible to encourage reuse.
3. This theme defines no custom post types or custom taxonomies. It shouldn't contain any non-presentational logic at all. This should all be custom plugins. A site plugin should be set up for post types and taxonomies.

## Quickstart

**This project requires [Node.js](http://nodejs.org) v4.x.x to v6.11.x to be installed on your machine.** Please be aware that you might encounter problems with the installation if you are using the most current Node version (bleeding edge) with all the latest features.

### 1. Clone the repository and install with npm/yarn
```bash
$ cd my-wordpress-folder/wp-content/themes/
$ git clone https://github.com/allen-hc/hny-theme_boilerplate.git
$ mv hny-theme_boilerplate companyname
$ cd companyname
$ rm -rf .git
```

Edit the theme name in `style.css` and add the theme slug to the 'name' field in `package.json`.

At this point, upload the theme to your remote dev/production server and install the build system:

```bash
$ yarn install
```

### 2. ssh into the dev server while you're working on your project and run:

```bash
$ yarn run watch
```

Uploading .scss and .js files from your machine to the dev server will now trigger asset compilation.

### 3. For building all the assets, run:

```bash
$ yarn run build
```

Before launching a site, running this command will build all assets minified and without sourcemaps:
```bash
$ yarn run production
```

### 4. Project Structure

In the `/src` folder you will find the working source files for your project. Every time you make a change to a file that is watched by Gulp, the output will be saved to the `/dist` folder. The contents of this folder is the compiled code. You will never need to touch the contents of this folder.

Create PHP template partials in the `/partials` folder.

PHP helper functions are in the `/includes` folder.

##### Styles and Sass Compilation

 * `style.css`: Do not worry about this file. (For some reason) it's required by WordPress. All styling are handled in the Sass files described below

 * `src/styles/main.scss`: Globbed imports for all your modules happen here
 * `src/styles/common/*.scss`: Global settings
 * `src/styles/components/*.scss`: Stylesheets for main front-end components
 * `src/styles/modules/*.scss`: Topbar, footer etc.

 * `dist/styles/main.bundle.css`: This file is loaded in the `<head>` section of your document, and contains the compiled styles for your project.

If you're new to Sass, please note that you need to have Gulp running in the background (``yarn run watch``), for any changes in your scss files to be compiled to `main.css`.

##### JavaScript Compilation

All JavaScript files, including Foundation's modules, are imported through the `src/scripts/main.js` file. The files are imported using module dependency with [webpack](https://webpack.js.org/) as the module bundler.

If you're unfamiliar with modules and module bundling, check out [this resource for node style require/exports](http://openmymind.net/2012/2/3/Node-Require-and-Exports/) and [this resource to understand ES6 modules](http://exploringjs.com/es6/ch_modules.html).

Foundation modules are loaded in the `src/scripts/autoload/foundation.js` file. By default, the bare minimum modules are loaded. You can also pick and choose which modules to include: just uncomment the necessary lines in the file.

If you need to output additional JavaScript files separate from `main.js`, do the following:
* Create new `custom.js` file in `src/scripts/`. If you will be using jQuery, add `import $ from 'jquery';` at the top of the file.
* In `build/config.js`, add `src/scripts/custom.js` to `config.entries.scripts`.
* Build (`yarn run production`)
* You will now have a `custom.js` file outputted to the `dist/scripts` directory.

## Companion site plugin repo

Since post types and core site functionality shouldn't be defined in the theme but rather a plugin, Honeycomb Creative has a site plugin boilerplate repo as a companion to this one: https://github.com/allen-hc/hny-plugin_boilerplate.

## Setting up hny-theme_boilerplate and the hny-plugin_boilerplate

Follow these steps to set up a shiney new Wordpress theme, complete with the Honeycomb Creative plugin boilerplate.

### WP Theme Boilerplate

* Clone the wp theme boilerplate into the themes directory of your wp install ( https://github.com/allen-hc/hny-theme_boilerplate.git )
* Rename hny-theme_boilerplate directory to the name of your new theme
* In your new theme directory, delete .git directory and .gitignore file
* Open the style.css file in the root of the theme, and customize with information for your new project.

### Activate Theme
In wordpress admin, go to appearance / themes and activate your new wordpress theme.

### Plug In some Awesome stuff

The plugin boilerplate contains custom post types, taxonomies, and ACF field files. The purpose of containing these in a plugin, instead of in the theme, is to allow the separation of data from style.

* Navigate to plugins folder of your WP install and clone in the wp plugin boilerplate ( https://github.com/allen-hc/hny-plugin_boilerplate.git )
* Delete the .git directory and .gitignore file
* Rename the hny-plugin_boilerplate directory to the same name as your theme.
* Rename sitename.php file to the same name as your theme.
* Open sitename.php file and replace all instances of `SITE NAME` and `sitename` with the same name as your theme.
* In wordpress admin, go to plugins, and activate the plugin.
