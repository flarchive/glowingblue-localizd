# 🌍🌎🌏 Localizd

![License](https://img.shields.io/badge/license-%C2%A9%20Glowing%20Blue%20AG-%2300a7e3)

> [!CAUTION]
> **This extension is abandoned.** It is no longer maintained, and there are no plans for further development, bug fixes, or support. There is also no Flarum 2.x port planned. Use it at your own risk.

Localizd is a [Flarum](http://flarum.org) extension making Flarum a **truly multi-lingual** forum platform.

### 🛍 Features

Localizd enables (virtually) limitless possibilities to translate anything in flarum that is normally not translatable 💪 !

For example this makes it easy to translate the names of tags into all the different languages of your forum.

A full list of all the fields that can be translated with Localizd can be found in a dedicated section at the end of this document.

### 📥 Installation & 💳 Pricing

This is a premium extension and requires an active subscription via [Extiverse](https://extiverse.com/).

Installing premium extensions from [Extiverse](https://extiverse.com/) requires a special configuration for [`composer`](https://getcomposer.org/). [Extiverse](https://extiverse.com/) offers an explanation on how to do that on the [Subscriptions](https://extiverse.com/premium/subscriptions) page (requires you to be logged in).

After the configuration has been done, you can install Localizd as you would install any other package from [Packagist](https://packagist.org/):

```bash
# Install
composer require glowingblue/localizd:"*"
# Update
composer require glowingblue/localizd:"*"
```

### ✅ Requirements

> [!WARNING]
> While `localizd` still supports PHP 7.4 and PHP 8.0, the testing suite doesn't anymore. Support for PHP 7.4 and PHP 8.0 will be removed alltogether in the future.

Localizd requires the following:

-   PHP 7.4 _or_ PHP 8.x
-   `flarum/core` minimum `1.8.3`
-   [`fof/linguist`](https://github.com/FriendsOfFlarum/linguist) at least version `1.0.3`
-   [`symfony/yaml`](https://github.com/symfony/yaml) - any compatible version

These dependencies will be checked by composer when installing or updating this extension.

### 📖 Usage

As admin, navigate to any settings that you would like to translate and then use the **Translate** button: ![translate-ui](https://community.glowingblue.com//assets/files/2022-03-30/1648646511-326017-image.png)

This will open a pop-up where all the translations can be defined for that specific field.

**It is required to clear the cache after adding or changing translations!**

**Note:** In some cases, like creating a new tag, you cannot make the translations _while_ creating the content, but only after you saved it. See the screenshots below.

#### Usage example: translate tags

1. Create a new tag

![create-tag](https://community.glowingblue.com//assets/files/2022-03-30/1648647882-338574-image.png)

2. Edit the tag that has just been created to add translations

![tag-translation](https://community.glowingblue.com//assets/files/2022-03-30/1648647968-973865-image.png)

3. Clear the cache.

### 🆘 Support

If you need any help, found a bug or there is anything that you need us for, please open a discussion on [our forum](https://community.glowingblue.com/) in the [Localizd Support](https://community.glowingblue.com/t/localizd-support) section.

### 📌 List of translatable fields

#### `flarum/core`

##### Basics

-   Forum title
-   Forum description
-   Welcome title
-   Welcome message

##### Appearance

-   Custom header
-   Custom footer

#### `flarum/tags`

-   Tag name
-   Tag description

#### `flarum/flags`

-   Community guidelines URL

#### `fof/links`

-   Link title
-   Link URL

#### `fof/reactions`

-   Reaction display (name)

#### `fof/terms`

-   Policy name
-   Policy URL
-   Policy update message

#### `fof/cookie-consent`

-   Content message
-   Dismiss button text
-   Learn more text
-   Learn more button

#### `flamarkt/taxonomies`

-   Taxonomy name
-   Taxonomy description
-   Taxonomy Term name
-   Taxonomy Term description
