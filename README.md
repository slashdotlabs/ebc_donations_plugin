# EBC Donations

Simple WordPress plugin that adds a donation form on a specified page accepting payments from users.
This is not a general purpose plugin, but was designed to work on the Emmanuel Baptist Church website.

## Features
- Donation form shortcode.
- Integration with [iPay Africa](https://ipayafrica.com) payment gateway.
- Admin dashboard table to show donations made.

## Shortcodes
- ``ebc_donations_form`` for the Musyimi family fund page
- ``ebc_giving_form`` for the online giving page
- ``ebc_thank_you`` this basically wraps the contents between in a success div

## Tables Created
- ``{$prefix}ebc_donations`` donations made on the Musyimi fund
- ``{$prefix}ebc_giving`` donations made on the online giving page

## Required pages
- ``/thank-you`` this is the callback to the Musyimi fund transactions 
- ``/giving-thank-you`` this is the callback to the online giving fund transactions 

> The codebase and implementation is a little jumpy. Effects of rushed deliveries and poor planning.
