### Run test

docker run -v $(pwd):/app -w /app motorsportnetwork/phpunit --bootstrap vendor/autoload.php test/