<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage | Router boilerplate</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body style="font-family: Arial, Helvetica, sans-serif; display: grid; place-items: center">
    <div>
        <div style="margin-top: 100px; display: flex; align-items: center; gap: 20px;">
            <h1 style="margin: 0;">Homepage</h1>
            <big style="border-left: 1px solid black; padding: 10px 20px; font-size: 20px; line-height: 20px;">¯\_(ツ)_/¯</big>
        </div>
        <hr>
        <div style="margin-bottom: 100px;">
            <a href="<?= $this->link('homepage') ?>" class="<?= $this->getCurrentRoute() == "homepage" ? 'active' : '' ?>">Homepage</a>
            <a href="<?= $this->link('about') ?>" class="<?= $this->getCurrentRoute() == "about" ? 'active' : '' ?>">About us</a>
        </div>
    </div>
</body>
</html>