# OptimusCrime.net :: Personal Website

[optimuscrime.net](https://optimuscrime.net)

## What is this?

It is my personal blog, with the code behind both the development server, as well as the code that generates the
static content Docker image.

### Why write your own static content generator?

I have no idea.

### Why did you write it in PHP?

I have no idea.

## Run development image

This image runs a PHP built in server inside a Docker container.

Update your hosts id to point localhost or the Docker machine ip to `optimuscrime.local`

Build

```
make php-build
```

Run

```
make php-run
```

## Run production image

This image serves the static content from a Nginx web server.

Build

```
make nginx-build
```

Run

```
```

## Example production setup

```
version: '2'
services:
  personal-website-nginx:
    image: "optimuscrime/personal-website-nginx:latest"
    restart: unless-stopped
```