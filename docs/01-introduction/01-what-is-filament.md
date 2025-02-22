---
title: What is Filament?
contents: false
---

Filament is a meta-framework for Laravel. It aims to provide a set of components and conventions that make it easier to build the frontend for your Laravel applications using Livewire, Alpine.js, and Tailwind CSS. Filament is designed to be flexible and extensible, so you can use as much or as little of it as you like and customize it to fit your needs.

Thousands of developers choose Filament to add an admin panel interface to their app. You'll be able to do this regardless of what your app's frontend is built using: it's commonly paired with tools like Inertia.js that interface Laravel with a full frontend framework like Vue.js, React, or Svelte.

If you're building your app's frontend with Blade (Livewire or not), you'll be able to enrich your views with components from the framework to save yourself time. For example, the same form builder and table builder that powers our admin panel tool can be installed into a Livewire component. Livewire components can be inserted into any Blade view or rendered from a route, regardless of whether you're already using Livewire for your other Blade views or not.

Aside from building admin panels, Filament panels can be used to build a variety of interfaces in your app. For example, you could build a user dashboard in a SaaS app, or a CRM for a certain department in your organization. A single Laravel app can contain multiple panels, each with its own configuration for branding, navigation, and routing.

## Packages

The core of Filament is comprised of several packages:

- `filament/filament` - The core package for building panels (e.g., admin panels). This requires all the other packages since panels often use many of their features.
- `filament/tables` - An datatable builder that allows you to render an interactive table with filtering, sorting, pagination, and more.
- `filament/schemas` - A package that allows you to build UIs using an array of "component" PHP objects as configuration. This is used by many features in Filament to render UI. The package includes a base set of components that allow you to render content.
- `filament/forms` - A set of `filament/schemas` components for a large variety form inputs (fields), complete with integrated validation.
- `filament/infolists` - A set of `filament/schemas` components for rendering "description lists". An infolist is formed of "entries", which are key-value UI elements that can present read-only information like text, icons and images. The data for an infolist can be sourced from anywhere, but commonly an individual Eloquent record.
- `filament/actions` - Action objects encapsulate the UI for a button, an interactive modal window that can be opened by the button, and the logic that should be executed when the modal window is submitted. They can be used anywhere in the UI, and are commonly used to perform one-time actions like deleting a record, sending an email, or updating data in the database based on modal form input.
- `filament/notifications` - An easy way to send notifications to the user in the UI of your app. You can send a "flash" notification that appears immediately after a request they make to the server, a "database" notification which is stored in the database and rendered in a slide-over modal the user can open on demand, or a "broadcast" notification that is sent to the user in real-time over a websocket connection.
- `filament/widgets` - A set of dashboard "widgets" that can render anything, often statistical data. Charts, numbers, tables, and completely custom widgets can be rendered in a dashboard using this package.
- `filament/support` - This package contains a set of shared UI components and utilities used by all the other packages. Users don't commonly install this directly, but it is a dependency of all the other packages.

## Plugins

Filament is designed to be very extensible, so you can add your own UI components and features to the framework. These extensions can live inside your codebase if they're specific to your application, or can be distributed as Composer packages if they're general-purpose. These Composer packages are called "plugins" in the Filament ecosystem, and there are hundreds of them available from the community. The Filament team also maintains a few plugins officially, to provide integration with popular third-party packages in the Laravel ecosystem.

The vast majority of plugins in the ecosystem are open-source and free to use, and there are some premium plugins available for purchase, which sometimes offer a higher level of customer support and quality.

You can search a large list of official and community plugins on the [Filament website](/plugins).

## Customizing the appearance

Tailwind CSS is a utility-based CSS framework which Filament uses as a token-based design system. Although it does not use Tailwind CSS utility classes directly in the HTML rendered by the components, it compiles Tailwind utilities into semantic CSS. The semantic classes can be targeted by Filament users with their own CSS to modify the appearance of the components, creating a thin layer of overrides on top of the default Filament design.

A simple example to demonstrate the power of this system is to change the border radius of all button components in Filament. By default, the following CSS code is used in the Filament codebase to style buttons using Tailwind utility classes:

```css
.fi-btn {
    @apply rounded-lg px-3 py-2 text-sm font-medium outline-none;
}
```

To decrease the [border radius in Tailwind CSS](https://tailwindcss.com/docs/border-radius), you could apply the `rounded-sm` (small) class to `.fi-btn` in your own CSS file:

```css
.fi-btn {
    @apply rounded-sm;
}
```

This would override the default `rounded-lg` class with `rounded-sm` for all buttons in Filament, while preserving the other styling properties of the button. This system provides a high level of flexibility to customize the appearance of Filament components without needing to write a full custom stylesheet or maintain a copy of the HTML for each component.

For more information about customizing the appearance of Filament, visit the [Styling](../styling) section.

## Testing

The core packages in Filament are unit tested to maintain the stability of releases. Filament users are also able to write their own tests for applications built with the framework. Filament provides a set of utilities for testing the functionality and UI of components, which can be used in either Pest or PHPUnit test suites. Testing your application is especially important when you're customizing the framework or writing your own functionality, but you can also write tests for basic functionality to ensure that your app is working as expected.

For more information about testing Filament applications, visit the [Testing](../testing) section.
