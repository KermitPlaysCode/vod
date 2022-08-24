# VoD

## The content and the purpose

I had a library of videos, with info prepared for my Kodi by TinymediaManager (NFO and few images)
I wanted to be able to access this library through my personal HTTPS server.
I mean, view the files and stream the video.

It came to life when my upload bandwidth became strong enough.

## The quality

Don't expect good quality code. I enjoyed playing with PHP, HTML and CSS (my very first try).
The result is good enough for my need (and probably for my capabilities)

## What it needs

On the server side:

- a directory of movies, preferrably with their associated nfo
- a decent http server with PHP enabled (I use Debian with Lighttpd and php7.4)

On the client side:

- a computer with VLC

## How it starts

- Get the files, put them in a directory shared by the HTTP server
- Ensure the folder with video files is also shared by the HTTP server
- Edit config.php to suit the needs (be creative, there is no doc about the settings, I tried to give self-explanatory names)

## What it doesn't do

Any video compression/re-compression, neither offline, neither on-the-fly. Use any good open source software for that : give a try to Handbrake with GUI and ffmpeg with CLI.