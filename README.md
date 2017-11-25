# Custom Events plugin for Craft CMS 3.x

Trigger custom events from templates and plugins that other plugins can hook into.

![Screenshot](resources/img/icon.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require sjelfull/custom-events

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Custom Events.

## Custom Events Overview

-Insert text here-

## Configuring Custom Events

-Insert text here-

## Using Custom Events

### Create an event

```twig
{% do craft.customEvents.createEvent('viewedProposal') %}
```

### Create an event for an element

```twig
{% do craft.customEvents.createEventForElement('acceptedProposal', entry) %}
```

## Custom Events Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [Superbig](https://superbig.co)
